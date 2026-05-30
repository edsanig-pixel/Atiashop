<?php

namespace App\Helpers;

use App\Models\Invoice;
use Morilog\Jalali\Jalalian;

class InvoiceNumberHelper
{
    public static function generate($typeCode = '101')
    {
        $now = Jalalian::now();
        $yearMonth = $now->format('y') . $now->format('m');
        $prefix = "F-{$yearMonth}-{$typeCode}";

        $lastInvoice = Invoice::where('invoice_number', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastInvoice) {
            $lastSerial = (int) substr($lastInvoice->invoice_number, -3);
            $newSerial = str_pad($lastSerial + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newSerial = '001';
        }

        return $prefix . $newSerial;
    }
}