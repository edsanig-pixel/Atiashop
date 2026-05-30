<?php

namespace App\Services;

use App\Models\Product;
use App\Models\InventoryDoc;
use App\Models\InventoryItem;
use App\Models\JournalVoucher;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    /**
     * محاسبه قیمت تمام‌شده هر واحد کالا بر اساس روش میانگین موزون متحرک (Weighted Moving Average)
     *
     * @param int $productId
     * @return float میانگین قیمت تمام‌شده (به ریال)
     */
    public function calculateWeightedAverage(int $productId): float
    {
        // جمع‌آوری اطلاعات موجودی فعلی انبار (قبل از خروج)
        $currentStock = InventoryItem::whereHas('inventoryDoc', function ($query) {
                $query->where('status', 'finalized'); // فقط اسناد قطعی شده
            })
            ->where('product_id', $productId)
            ->select(DB::raw('SUM(quantity) as total_quantity'), DB::raw('SUM(calculated_unit_cost * quantity) as total_value'))
            ->first();

        $totalQuantity = (float) $currentStock->total_quantity ?? 0;
        $totalValue    = (float) $currentStock->total_value ?? 0;

        // اگر موجودی فعلی صفر است، از قیمت خرید پایه محصول استفاده شود (قیمت خرید دیتابیس)
        if ($totalQuantity <= 0) {
            $product = Product::find($productId);
            return (float) $product->purchase_price ?? 0;
        }

        // محاسبه میانگین وزنی
        return $totalValue / $totalQuantity;
    }

    /**
     * ثبت سند خروج از انبار (حواله) پس از محاسبه بهای تمام‌شده
     * 
     * این متد در زمان امضای سوم فاکتور فروش فراخوانی می‌شود.
     * پس از محاسبه نرخ، داده‌های مورد نیاز برای سند حسابداری را بازمی‌گرداند.
     *
     * @param array $invoiceData اطلاعات فاکتور فروش (محصولات، تعداد، قیمت فروش، ...)
     * @return array آرایه‌ای شامل:
     *               - inventory_doc: سند انبار ثبت‌شده
     *               - accounting_items: آرتیکل‌های بدهکار/بستانکار (بهای تمام‌شده و موجودی کالا)
     */
    public function createInventoryDocForSale(array $invoiceData): array
    {
        $docNumber = $this->generateInventoryDocNumber(); // شماره سند انبار (اختیاری)

        return DB::transaction(function () use ($invoiceData, $docNumber) {
            // ۱. ایجاد هدر سند انبار (حواله خروج)
            $inventoryDoc = InventoryDoc::create([
                'doc_type'      => 'issue', // خروج از انبار
                'source_type'   => 'invoice',
                'source_id'     => $invoiceData['invoice_id'] ?? null,
                'doc_number'    => $docNumber,
                'status'        => 'finalized',
                'issued_at'     => now(),
            ]);

            $totalCostValue = 0; // جمع ارزش ریالی بهای تمام‌شده (برای انتقال به سرویس حسابداری)
            $inventoryItems = [];

            foreach ($invoiceData['items'] as $item) {
                // محاسبه میانگین موزون قیمت تمام‌شده برای این کالا
                $unitCost = $this->calculateWeightedAverage($item['product_id']);
                $totalRowCost = $unitCost * $item['quantity'];

                // آماده‌سازی رکورد آیتم انبار
                $inventoryItems[] = [
                    'inventory_doc_id'      => $inventoryDoc->id,
                    'product_id'            => $item['product_id'],
                    'quantity'              => $item['quantity'],
                    'calculated_unit_cost'  => $unitCost,     // قیمت تمام‌شده هر واحد
                    'total_cost_value'      => $totalRowCost,
                ];

                $totalCostValue += $totalRowCost;
            }

            // ذخیره‌سازی یکجای آیتم‌های انبار (Bulk Insert با زمان)
            $now = now();
            $preparedRows = array_map(function ($row) use ($now) {
                return array_merge($row, [
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }, $inventoryItems);
            InventoryItem::insert($preparedRows);

            // ۲. آماده‌سازی آرتیکل‌های حسابداری برای ارسال به JournalEntryService
            //    (بدهکار: بهای تمام‌شده کالای فروش رفته | بستانکار: موجودی انبار)
            $accountingItems = [
                [
                    'account_id'   => 511001, // معین بهای تمام‌شده کالای فروش رفته (بر اساس کدینگ)
                    'debit'        => $totalCostValue,
                    'credit'       => 0,
                    'row_description' => 'بهای تمام‌شده کالای فروخته شده - فاکتور شماره ' . ($invoiceData['invoice_number'] ?? ''),
                ],
                [
                    'account_id'   => 111502, // معین موجودی انبار کالای تجاری
                    'debit'        => 0,
                    'credit'       => $totalCostValue,
                    'row_description' => 'کاهش موجودی انبار بابت خروج کالا - فاکتور شماره ' . ($invoiceData['invoice_number'] ?? ''),
                ],
            ];

            return [
                'inventory_doc'      => $inventoryDoc,
                'accounting_items'   => $accountingItems,
                'total_cost_value'   => $totalCostValue,
            ];
        });
    }

    /**
     * تولید شماره یکتا برای سند انبار (اختیاری، قابل تنظیم)
     */
    private function generateInventoryDocNumber(): string
    {
        $lastDoc = InventoryDoc::orderBy('id', 'desc')->first();
        $lastNumber = $lastDoc ? (int) substr($lastDoc->doc_number, -6) : 0;
        $newNumber = str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);
        return 'INV-' . $newNumber;
    }
}