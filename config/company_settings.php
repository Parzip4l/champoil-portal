<?php

return [
    'keys' => [
        'use_shift',
        'use_schedule',
        'use_multilocation',
        'late_cut_enabled',
        'late_minutes_threshold',
        'late_cut_amount',
        'payroll_type',
        'payroll_structure',
        'cutoff_start',
        'cutoff_end',
        'use_pph21',
        'pph21_method',
        'npwp_required',
        'use_radius',
        'radius_value',
        'grace_period',
        'gps_coordinates',
        'attendance_mode',
        'default_in_time',
        'default_out_time',
        'late_tolerance',
        'workdays',
        'annual_leave_quota',
        'max_leave_accumulation',
        'allow_leave_conversion',
        'leave_conversion_amount',
    ],

    'validation_rules' => [
        'use_shift' => ['nullable', 'boolean'],
        'use_schedule' => ['nullable', 'boolean'],
        'use_multilocation' => ['nullable', 'boolean'],

        'late_cut_enabled' => ['nullable', 'boolean'],
        'late_minutes_threshold' => ['nullable', 'numeric', 'min:0'],
        'late_cut_amount' => ['nullable', 'numeric', 'min:0'],

        'payroll_type' => ['nullable', 'in:monthly,weekly,biweekly'],
        'payroll_structure' => ['nullable', 'in:sama,berbeda'],
        'cutoff_start' => ['nullable', 'numeric', 'between:1,31'],
        'cutoff_end' => ['nullable', 'numeric', 'between:1,31'],

        'use_pph21' => ['nullable', 'boolean'],
        'pph21_method' => ['nullable', 'in:gross,net,gross-up'],
        'npwp_required' => ['nullable', 'boolean'],

        'use_radius' => ['nullable', 'boolean'],
        'radius_value' => ['nullable', 'numeric', 'min:0'],
        'gps_coordinates' => ['nullable', 'string'],

        'attendance_mode' => ['nullable', 'in:gps,qrcode,manual'],

        'default_in_time' => ['nullable', 'date_format:H:i'],
        'default_out_time' => ['nullable', 'date_format:H:i'],
        'late_tolerance' => ['nullable', 'numeric', 'min:0'],
        'grace_period' => ['nullable', 'numeric', 'min:0'],

        'workdays' => ['nullable', 'array'],
        'workdays.*' => ['in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu'],

        'annual_leave_quota' => ['nullable', 'numeric', 'min:0'],
        'max_leave_accumulation' => ['nullable', 'numeric', 'min:0'],
        'allow_leave_conversion' => ['nullable', 'boolean'],
        'leave_conversion_amount' => ['nullable', 'numeric', 'min:0'],
    ],
];
