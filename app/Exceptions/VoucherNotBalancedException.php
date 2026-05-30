<?php

namespace App\Exceptions;

use Exception;

/**
 * استثنای اختصاصی برای زمان تراز نبودن مبالغ بدهکار و بستانکار سند
 */
class VoucherNotBalancedException extends Exception
{
    // این کلاس صرفاً برای تفکیک نوع خطا در لایه کنترلر استفاده می‌شود.
}