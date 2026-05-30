<?php

namespace App\Services;

use App\Models\JournalVoucher;
use App\Models\JournalItem;
use App\Exceptions\VoucherNotBalancedException;
use Illuminate\Support\Facades\DB;
use Exception;

class JournalEntryService
{
    /**
     * ثبت یک سند حسابداری کامل (هدر + آرتیکل‌ها) به صورت کاملاً تراکنشی و ضدگلوله
     * * @param array $voucherData اطلاعات هدر سند (تاریخ، شرح، وضعیت، کاربر و...)
     * @param array $itemsData آرایه‌ای از آرتیکل‌های بدهکار و بستانکار
     * @return JournalVoucher
     * @throws VoucherNotBalancedException|Exception
     */
    public function createEntry(array $voucherData, array $itemsData): JournalVoucher
    {
        // گام ۱: اعتبارسنجی تراز بودن سند قبل از ورود به تراکنش دیتابیس
        $this->validateBalance($itemsData);

        // گام ۲: شروع تراکنش واحد دیتابیس (All or Nothing)
        return DB::transaction(function () use ($voucherData, $itemsData) {
            
            // ۱. ایجاد رکورد هدر سند حسابداری
            $voucher = JournalVoucher::create($voucherData);

            // ۲. آماده‌سازی آرتیکل‌ها و تزریق زمان دقیق (حل باگ Bulk Insert)
            $now = now();
            $preparedItems = [];

            foreach ($itemsData as $item) {
                $preparedItems[] = [
                    'journal_voucher_id'      => $voucher->id,
                    'account_id'              => $item['account_id'],
                    'person_detailed_id'      => $item['person_detailed_id'] ?? null,
                    'bank_cash_detailed_id'   => $item['bank_cash_detailed_id'] ?? null,
                    'cost_center_detailed_id' => $item['cost_center_detailed_id'] ?? null,
                    'project_detailed_id'     => $item['project_detailed_id'] ?? null,
                    'debit'                   => $item['debit'] ?? 0.00,
                    'credit'                  => $item['credit'] ?? 0.00,
                    'row_description'         => $item['row_description'] ?? null,
                    'created_at'              => $now,
                    'updated_at'              => $now,
                ];
            }

            // ۳. درج فوق‌سریع و یکجای آرتیکل‌ها در دیتابیس با حفظ Timestamps
            JournalItem::insert($preparedItems);

            // اگر سند در وضعیت تایید نهایی (finalized) صادر شده، شماره سند قطعی تخصیص یابد
            if (isset($voucherData['status']) && $voucherData['status'] === 'finalized') {
                $this->generateFinalVoucherNumber($voucher);
            }

            return $voucher;
        });
    }

    /**
     * اعتبارسنجی ریاضی تراز سند حسابداری
     */
    private function validateBalance(array $itemsData): void
    {
        $totalDebit = 0;
        $totalCredit = 0;

        foreach ($itemsData as $item) {
            $totalDebit += $item['debit'] ?? 0;
            $totalCredit += $item['credit'] ?? 0;
        }

        // بررسی اختلاف با در نظر گرفتن خطای محاسبات اعشاری دیتابیس (کمتر از یک ریال)
        $difference = abs($totalDebit - $totalCredit);
        if ($difference > 0.01) {
            $formattedDiff = number_format($difference, 2);
            throw new VoucherNotBalancedException(
                "سند مالی تراز نیست. مجموع بدهکار و بستانکار باید برابر باشد. اختلاف: {$formattedDiff} ریال"
            );
        }
    }

    /**
     * تولید شماره سند قطعی سیستمی بدون جا افتادگی (Gap)
     */
    private function generateFinalVoucherNumber(JournalVoucher $voucher): void
    {
        // قفل کردن ردیف برای جلوگیری از تداخل شماره‌ها در درخواست‌های همزمان (Concurrency)
        $lastVoucherNumber = JournalVoucher::whereNotNull('voucher_number')
            ->lockForUpdate()
            ->max('voucher_number');

        $nextNumber = $lastVoucherNumber ? ((int)$lastVoucherNumber + 1) : 1;
        
        // فرمت‌دهی شماره سند به صورت ۶ رقمی با صفرهای پیشرو (مثال: 000001)
        $voucher->update([
            'voucher_number' => str_pad($nextNumber, 6, '0', STR_PAD_LEFT)
        ]);
    }
}