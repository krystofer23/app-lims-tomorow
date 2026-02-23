<?php

namespace App\Casts;

use App\Helper;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class LocalTimezone implements CastsAttributes
{
    public function get($model, $key, $value, $attributes)
    {
        if (! $value) {
            return null;
        }

        try {
            // $timezone = Helper::getUserEstablishment()?->timezone ?? 'America/Lima';
            $timezone = 'America/Lima';
        } catch (\Exception $e) {
            $timezone = 'America/Lima';
        }

        return Carbon::parse($value)->setTimezone($timezone)->toDateTimeString();
    }

    public function set($model, $key, $value, $attributes)
    {
        return $value ? Carbon::parse($value)->setTimezone('America/Lima') : null;
    }
}
