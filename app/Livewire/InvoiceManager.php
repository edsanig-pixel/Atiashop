<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Party;
use App\Models\Product;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\StockMovement;
use App\Models\Transaction;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use App\Helpers\InvoiceNumberHelper;

class InvoiceManager extends Component
{
    // بخش مشتری و فاکتور
    public $customer_id;
    public $receiver_name;
    public $delivery_method = 'internal';
    public $address;
    public $phone;
    public $seller_name;
    public $project_code;
    public $subject;
    public $register_number;
    public $order_number;
    public $serial_number;
    public $invoice_number;
    public $customer_debt = 0;

    // بخش محصولات و سبد خرید
    public $items = [];
    public $selected_product_id;
    public $selected_product;
    public $temp_quantity = 1;
    public $temp_unit_price = 0;
    public $temp_discount_percent = 0;
    public $temp_packing_cost = 0;
    public $temp_extra_cost = 0;
    public $temp_staff_cost = 0;

    // بخش مالی
    public $subtotal = 0;
    public $enable_discount = false;
    public $discount_percent = 0;
    public $discount_total = 0;
    public $enable_tax = false;
    public $tax_rate = 10;
    public $tax_amount = 0;
    public $delivery_cost = 0;
    public $extra_charges_total = 0;
    public $final_amount = 0;

    // روش پرداخت
    public $payment_type = 'cash';

    // مودال حذف
    public $showDeleteModal = false;
    public $deleteIndex = null;

    // واحد پول جاری
    public $currencyUnit;

    protected $rules = [
        'customer_id' => 'required|exists:parties,id',
        'items' => 'required|array|min:1',
        'items.*.quantity' => 'required|numeric|min:1',
        'items.*.unit_price' => 'required|numeric|min:0',
    ];

    public function mount()
{
    $this->items = [];
    $this->invoice_number = InvoiceNumberHelper::generate('101');
    $this->calculateTotals();
    $this->payment_type = 'cash';
    $this->currencyUnit = Setting::get('currency', 'ریال');
    
    // مقدار پیش‌فرض تخفیف و مالیات را غیرفعال کنید
    $this->enable_discount = false;
    $this->enable_tax = false;
    
    // ولی نرخ‌ها را از تنظیمات بگیرید (برای زمانی که کاربر فعال می‌کند)
    $this->tax_rate = Setting::get('tax_rate', 10);
    $this->discount_percent = 0; // تخفیف درصدی پیش‌فرض صفر
}

    // تبدیل از واحد جاری به ریال (برای ذخیره در دیتابیس)
    private function convertToRial($amount)
    {
        if ($this->currencyUnit == 'تومان') {
            return $amount * 10;
        }
        return $amount;
    }

    // تبدیل از ریال به واحد جاری (برای نمایش در فرم)
    private function convertFromRial($amountInRial)
    {
        if ($this->currencyUnit == 'تومان') {
            return $amountInRial / 10;
        }
        return $amountInRial;
    }

    // ==========  انتخاب محصول  ==========
    public function updatedSelectedProductId($value)
    {
        if ($value) {
            $this->selected_product = Product::with('unit')->find($value);
            if ($this->selected_product) {
                // قیمت فروش محصول از دیتابیس (ریال) به واحد جاری تبدیل می‌شود
                $this->temp_unit_price = $this->convertFromRial($this->selected_product->sale_price);
                $this->temp_quantity = 1;
                $this->temp_discount_percent = 0;
                $this->temp_packing_cost = 0;
                $this->temp_extra_cost = 0;
                $this->temp_staff_cost = 0;
            }
        } else {
            $this->selected_product = null;
        }
    }

    // ==========  افزودن کالا به سبد  ==========
    public function addItem()
    {
        $this->validate([
            'selected_product_id' => 'required|exists:products,id',
            'temp_quantity' => 'required|numeric|min:1',
            'temp_unit_price' => 'required|numeric|min:0',
            'temp_discount_percent' => 'nullable|numeric|min:0|max:100',
            'temp_packing_cost' => 'nullable|numeric|min:0',
            'temp_extra_cost' => 'nullable|numeric|min:0',
            'temp_staff_cost' => 'nullable|numeric|min:0',
        ]);

        $product = $this->selected_product;
        if (!$product) return;

        if ($product->stock < $this->temp_quantity) {
            session()->flash('error', "موجودی محصول {$product->name} کافی نیست (موجودی: {$product->stock})");
            return;
        }

        // تبدیل قیمت واحد وارد شده (تومان/ریال) به ریال برای محاسبات دیتابیس
        $unitPriceInRial = $this->convertToRial($this->temp_unit_price);
        $discount_amount = ($unitPriceInRial * $this->temp_quantity) * ($this->temp_discount_percent / 100);
        $subtotal_row = ($unitPriceInRial * $this->temp_quantity) - $discount_amount;

        $item = [
            'product_id' => $product->id,
            'name' => $product->name,
            'sku' => $product->sku,
            'unit_name' => ($product->unit) ? $product->unit->title : 'عدد',
            'quantity' => $this->temp_quantity,
            'unit_price' => $unitPriceInRial,               // ذخیره به ریال در سبد
            'unit_price_display' => $this->temp_unit_price, // برای نمایش در ویو
            'discount_percent' => $this->temp_discount_percent,
            'discount_amount' => $discount_amount,
            'packing_cost' => $this->temp_packing_cost ?: 0,
            'extra_cost' => $this->temp_extra_cost ?: 0,
            'staff_cost' => $this->temp_staff_cost ?: 0,
            'subtotal' => $subtotal_row,
            'stock_at_sale' => $product->stock,
        ];

        $this->items[] = $item;
        $this->reset(['selected_product_id', 'selected_product', 'temp_quantity', 'temp_unit_price', 'temp_discount_percent', 'temp_packing_cost', 'temp_extra_cost', 'temp_staff_cost']);
        $this->calculateTotals();
    }

    // ==========  حذف کالا از سبد  ==========
    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
        $this->calculateTotals();
    }

    public function openDeleteModal($index)
    {
        $this->deleteIndex = $index;
        $this->showDeleteModal = true;
    }

    public function confirmDelete()
    {
        if (!is_null($this->deleteIndex)) {
            $this->removeItem($this->deleteIndex);
        }
        $this->showDeleteModal = false;
        $this->deleteIndex = null;
    }

    // ==========  محاسبات جمع و تخفیف و مالیات  ==========
    public function calculateTotals()
    {
        $this->subtotal = 0;
        $this->extra_charges_total = 0;
        foreach ($this->items as $item) {
            $this->subtotal += $item['subtotal'];
            $this->extra_charges_total += ($item['packing_cost'] + $item['extra_cost'] + $item['staff_cost']);
        }

        // تخفیف کلی (درصدی)
        if ($this->enable_discount && $this->discount_percent > 0) {
            $this->discount_total = $this->subtotal * ($this->discount_percent / 100);
        } else {
            $this->discount_total = 0;
        }

        $after_discount = $this->subtotal - $this->discount_total;

        // مالیات (فقط در صورت فعال بودن)
        if ($this->enable_tax) {
            $this->tax_amount = $after_discount * ($this->tax_rate / 100);
        } else {
            $this->tax_amount = 0;
        }

        $this->final_amount = $after_discount + $this->tax_amount + $this->extra_charges_total + $this->delivery_cost;
    }

    // لایو وایر به‌روزرسانی‌های خودکار
    public function updatedEnableDiscount() { $this->calculateTotals(); }
    public function updatedDiscountPercent() { $this->calculateTotals(); }
    public function updatedEnableTax() { $this->calculateTotals(); }
    public function updatedTaxRate() { $this->calculateTotals(); }
    public function updatedDeliveryCost() { $this->calculateTotals(); }

    // ==========  انتخاب مشتری و پر کردن خودکار آدرس، تلفن، بدهی  ==========
    public function updatedCustomerId($value)
    {
        if ($value) {
            $customer = Party::find($value);
            if ($customer) {
                $this->address = $customer->address;
                $this->phone = $customer->mobile;

                // محاسبه بدهی واقعی مشتری (فاکتورهای نسیه پرداخت‌نشده)
                $this->customer_debt = Invoice::where('customer_id', $value)
                    ->where('payment_type', 'credit')
                    ->where('status', '!=', 'paid')
                    ->get()
                    ->sum(function ($invoice) {
                        return $invoice->final_amount - ($invoice->paid_amount ?? 0);
                    });
            }
        }
        $this->dispatch('$refresh');
    }

    // ==========  ثبت نهایی فاکتور ==========
    public function saveInvoice()
    {
		
        $this->validate([
            'customer_id' => 'required|exists:parties,id',
            'items' => 'required|array|min:1',
        ]);

        // بررسی موجودی لحظه قبل از ثبت
        foreach ($this->items as &$item) {
            $product = Product::find($item['product_id']);
            if (!$product || $product->stock < $item['quantity']) {
                session()->flash('error', "موجودی محصول {$item['name']} کافی نیست.");
                return;
            }
            $item['stock_at_sale'] = $product->stock;
        }

        DB::beginTransaction();
        try {
            $invoice_number = InvoiceNumberHelper::generate('101');

            $invoice = Invoice::create([
                'invoice_number' => $invoice_number,
                'customer_id' => $this->customer_id,
                'invoice_type' => 'sales',
                'receiver_name' => $this->receiver_name,
                'delivery_method' => $this->delivery_method,
                'address' => $this->address,
                'phone' => $this->phone,
                'seller_name' => $this->seller_name,
                'project_code' => $this->project_code,
                'subject' => $this->subject,
                'register_number' => $this->register_number,
                'order_number' => $this->order_number,
                'serial_number' => $this->serial_number,
                'total_amount' => $this->subtotal,
                'delivery_cost' => $this->delivery_cost,
                'discount_total' => $this->discount_total,
                'tax_rate' => $this->enable_tax ? $this->tax_rate : 0,
                'tax_amount' => $this->tax_amount,
                'extra_charges_total' => $this->extra_charges_total,
                'final_amount' => $this->final_amount,
                'payment_type' => $this->payment_type,
                'paid_amount' => ($this->payment_type == 'cash') ? $this->final_amount : 0,
                'status' => ($this->payment_type == 'cash') ? 'paid' : 'pending',
            ]);

            foreach ($this->items as $item) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['unit_price'],
                    'subtotal' => $item['subtotal'],
                    'discount_percent' => $item['discount_percent'],
                    'discount_amount' => $item['discount_amount'],
                    'packing_cost' => $item['packing_cost'],
                    'extra_cost' => $item['extra_cost'],
                    'staff_cost' => $item['staff_cost'],
                    'stock_at_sale' => $item['stock_at_sale'],
                    'unit_name' => $item['unit_name'],
                ]);

                $product = Product::find($item['product_id']);
                $product->decrement('stock', $item['quantity']);

                StockMovement::create([
                    'product_id' => $item['product_id'],
                    'type' => 'out',
                    'quantity' => $item['quantity'],
                    'reference' => "invoice_id:{$invoice->id}",
                ]);
            }

            Transaction::create([
                'party_id' => $this->customer_id,
                'amount' => $this->final_amount,
                'type' => 'sale',
                'description' => "فاکتور شماره {$invoice->invoice_number}",
            ]);

            DB::commit();
            session()->flash('message', "فاکتور با موفقیت ثبت شد. شماره: {$invoice_number}");
            return redirect()->route('invoices.index');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'خطا در ثبت فاکتور: ' . $e->getMessage());
        }
    }

    public function render()
{
    $customers = Party::where('is_customer', 1)->orderBy('name')->get();
    $products = Product::with('unit')->orderBy('name')->get();
    $currencyUnit = Setting::get('currency', 'ریال'); // یا از $this->currencyUnit استفاده کنید
    return view('livewire.invoice-manager', compact('customers', 'products', 'currencyUnit'))->layout('layouts.app');
}
}