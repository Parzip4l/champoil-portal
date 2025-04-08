<?php

namespace App\Helpers;

use App\Company\CompanyModel;
use App\Company\CompanySetting;

class CompanySettingHelper
{
    public static function get($company_id, $key = null, $default = null)
    {
        $settings = CompanySetting::where('company_id', $company_id)
            ->pluck('value', 'key')
            ->toArray();

        // Decode JSON yang perlu didecode
        foreach ($settings as $k => $v) {
            // Cek dulu apakah value-nya string sebelum decode
            if (is_string($v)) {
                $decoded = json_decode($v, true);
                $settings[$k] = (json_last_error() === JSON_ERROR_NONE) ? $decoded : $v;
            } else {
                $settings[$k] = $v; // Kalau bukan string, biarin aja
            }
        }

        return $key ? ($settings[$key] ?? $default) : $settings;
    }
}
