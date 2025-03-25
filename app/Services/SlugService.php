<?php

namespace App\Services;

use Sqids\Sqids;

class SlugService
{
    public static function generateSlug()
    {
        $sqids = new Sqids();
        $uuid = Str::uuid();
        return $sqids->encode($uuid);
    }
}
