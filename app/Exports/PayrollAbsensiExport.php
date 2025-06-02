<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PayrollAbsensiExport implements WithMultipleSheets
{
    protected $periode;

    public function __construct($periode)
    {
        $this->periode = $periode;
    }

    public function sheets(): array
    {
        return [
            new PayrollSheet($this->periode),
            new AbsensiSheet($this->periode),
        ];
    }
}
