<?php

namespace App\Support;

class Logo
{
    public static function url(): ?string
    {
        foreach (['png', 'jpg', 'jpeg', 'webp', 'svg'] as $ext) {
            foreach (["img/logo.$ext", "img/logo/logo.$ext"] as $rel) {
                if (is_file(public_path($rel))) {
                    return asset($rel);
                }
            }
        }

        return null;
    }
}
