<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait CacheTrait
{
    public static function cache($guid) {
        $key = sprintf('%s_%s', static::class, $guid);

        if ( ! Cache::has($key)) {
            Cache::put($key, static::find($guid), 60);
        }

        return Cache::get($key)->replicate();
    }
}
