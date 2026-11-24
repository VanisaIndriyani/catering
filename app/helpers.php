<?php

use App\Support\Format;

if (! function_exists('rupiah')) {
    function rupiah(mixed $amount): string
    {
        return Format::rupiah($amount);
    }
}
