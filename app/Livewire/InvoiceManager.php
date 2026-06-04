<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\{Party, Product, Setting};
use App\Services\InvoiceFinalizationService;
use Illuminate\Support\Facades\DB;
use App\Helpers\InvoiceNumberHelper;

class InvoiceManager extends Component
{
    // ۱. فیلدهای اطلاعات مشتری و عمومی فاکتور (مطابق تب اول فرانت)
    public $customer_id;
    public $address;
    public $phone;
    public $subject;
    public $receiver_name;
    public $delivery_method = 'internal';
    public $seller_name;
    public $register_number;
    public $order_number;
    public $serial_number;

    // ۲. اقلام فاکتور و فیلدهای موقت افزودن کالا (مطابق تب دوم فرانت)
    public $items = [];
    public $selected_product_id;
    public $temp_quantity = 1;
    public $temp_unit_price = 0;
    public $temp_discount_percent = 0;
    public $temp_packing_cost = 0;
    public $temp_extra_cost = 0;
    public $temp_staff_cost = 0;

    // ۳. فیلدهای مالی و محاسباتی (مطابق تب سوم فرانت)
    public $subtotal = 0;
    public $enable_discount = false;
    public $discount_percent = 0;
    public $discount_total = 0;
    public $enable_tax = false;
    public $tax_rate = 0;
    public $tax_amount = 0;
    public $extra_charges_total = 0;
    public $final_amount = 0;
    public $customer_debt = 0;
    public $payment_type = 'cash';

    // ۴. ساختار اصلی سیستم
    public $currencyUnit;
    public $invoice_number;
    public $invoice_date;

    // ۵. وضعیت مودال حذف پاپ‌آپ
    public $showDeleteModal = false;
    public $deleteIndex = null;
    
    public function mount()
    {
        $this->items = [];
        $this->currencyUnit = Setting::get('currency', 'ریال');
        $this->invoice_date = now()->format('Y-m-d');
        $this->invoice_number = InvoiceNumberHelper::generate('101');
    }
	
	public function updatedCustomerId($value)
    {
        if ($value) {
            $customer = Party::find($value);
            if ($customer) {
                $this->address = $customer->address;
                $this->phone = $customer->phone;
                $this->customer_debt = $customer->debt ?? 0; // فرض بر وجود فیلد بدهی
            }
        } else {
            $this->reset(['address', 'phone', 'customer_debt']);
        }
    }

    // هوک لایووایر: وقتی کالا انتخاب شد، قیمت واحد آن خودکار در اینپوت قرار بگیرد
    public function updatedSelectedProductId($value)
    {
        if ($value) {
            $product = Product::find($value);
            if ($product) {
                $this->temp_unit_price = $product->sale_price;
            }
        } else {
            $this->temp_unit_price = 0;
        }
    }

    public function addItem()
    {
        $this->validate([
            'selected_product_id' => 'required|exists:products,id',
            'temp_quantity' => 'required|numeric|min:1',
            'temp_unit_price' => 'required|numeric|min:0',
        ], [
            'selected_product_id.required' => 'انتخاب کالا الزامی است.',
            'temp_quantity.required' => 'تعداد کالا را وارد کنید.',
            'temp_quantity.min' => 'تعداد نمی‌تواند کمتر از ۱ باشد.',
            'temp_unit_price.required' => 'قیمت واحد الزامی است.',
        ]);

        $product = Product::findOrFail($this->selected_product_id);

        if ($product->stock < $this->temp_quantity) {
            $this->addError('temp_quantity', 'موجودی کافی نیست!');
            return;
        }
		// محاسبه ساب‌توتال هر ردیف (تعداد × قیمت واحد)
        $row_subtotal = $this->temp_unit_price * $this->temp_quantity;

        $this->items[] = [
            'product_id' => $product->id,
            'sku' => $product->sku ?? $product->code ?? '—',
            'name' => $product->name,
            'quantity' => $this->temp_quantity,
            'unit_name' => $product->unit->title ?? 'عدد',
            'unit_price' => $this->temp_unit_price,
            'discount_percent' => $this->temp_discount_percent ?? 0,
            'packing_cost' => $this->temp_packing_cost ?? 0,
            'extra_cost' => $this->temp_extra_cost ?? 0,
            'staff_cost' => $this->temp_staff_cost ?? 0,
            'stock_at_sale' => $product->stock,
            'subtotal' => $row_subtotal,
        ];

        $this->calculateTotals();
        $this->reset([
            'selected_product_id', 'temp_quantity', 'temp_unit_price', 
            'temp_discount_percent', 'temp_packing_cost', 'temp_extra_cost', 'temp_staff_cost'
        ]);
    }
	
	public function openDeleteModal($index)
    {
        $this->deleteIndex = $index;
        $this->showDeleteModal = true;
    }

    public function confirmDelete()
    {
        if (isset($this->items[$this->deleteIndex])) {
            unset($this->items[$this->deleteIndex]);
            $this->items = array_values($this->items); // بازسازی ایندکس‌های آرایه
        }
        $this->showDeleteModal = false;
        $this->deleteIndex = null;
        $this->calculateTotals();
    }

	
	public function calculateTotals()
    {
        // ۱. جمع فاکتور قبل تخفیف
        $this->subtotal = array_sum(array_column($this->items, 'subtotal'));
        
        // ۲. جمع کل افزایشات کالاها (هزینه بسته‌بندی + سایر افزایشات) بر اساس هر ردیف
        $this->extra_charges_total = array_sum(array_map(function($item) {
            return ($item['packing_cost'] ?? 0) + ($item['extra_cost'] ?? 0);
        }, $this->items));
        
        // ۳. محاسبه تخفیف کلی فاکتور
        if ($this->enable_discount && $this->discount_percent > 0) {
            $this->discount_total = ($this->subtotal * $this->discount_percent) / 100;
        } else {
            $this->discount_total = 0;
        }
        
        // پایه محاسبه مالیات
        $base_for_tax = $this->subtotal - $this->discount_total + $this->extra_charges_total;

        // ۴. محاسبه مالیات
        if ($this->enable_tax && $this->tax_rate > 0) {
            $this->tax_amount = ($base_for_tax * $this->tax_rate) / 100;
        } else {
            $this->tax_amount = 0;
        }
        
        // ۵. مبلغ نهایی قابل پرداخت
        $this->final_amount = $base_for_tax + $this->tax_amount;
    }

   	public function saveInvoice()
    {
        $this->validate([
            'customer_id' => 'required', 
            'items' => 'required|min:1'
        ], [
            'customer_id.required' => 'انتخاب خریدار الزامی است.',
            'items.required' => 'لیست کالاهای فاکتور نمی‌تواند خالی باشد.',
            'items.min' => 'حداقل باید یک کالا به فاکتور اضافه کنید.'
        ]);

        DB::beginTransaction();
        try {
            $finalizer = app(InvoiceFinalizationService::class);
            
            $finalizer->finalize([
                'customer_id' => $this->customer_id,
                'items' => $this->items,
                'total_amount' => $this->final_amount,
                'invoice_number' => $this->invoice_number,
                // در صورت نیاز می‌توانی فیلدهای جدید (آدرس، روش پرداخت و...) را هم به سرویس نهایی‌سازی‌ات پاس بدهی
            ]);

            DB::commit();
            session()->flash('message', 'فاکتور با موفقیت به صورت قطعی ثبت شد.');
            return redirect()->route('invoices.index');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'خطا در ثبت فاکتور: ' . $e->getMessage());
        }
    }
	
	

    public function render()
    {
        return view('livewire.invoice-manager', [
            'customers' => Party::where('is_customer', 1)->get(),
            'products' => Product::where('is_active', 1)->get(),
			// حل قطعی خطای اصلی شما: پاس دادن شیء محصول انتخاب شده به فرانت برای نمایش زنده موجودی انبار
            'selected_product' => $this->selected_product_id ? Product::with('unit')->find($this->selected_product_id) : null,
        ])->layout('layouts.app');
    }
}