<?php

namespace App\Support;

class Format
{
    public static function rupiah(mixed $amount): string
    {
        return 'Rp '.number_format((float) $amount, 0, ',', '.');
    }
}
