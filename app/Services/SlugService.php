<?php

namespace App\Services;

use Str;

class SlugService
{
    public static function generateSlug()
    {
        $uuid = explode('-', Str::uuid());
        $uuid = $uuid[0] . array_pop($uuid);
        return $uuid;
    }
}
