<?php

namespace App\Exports;

use App\Payrollns;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PayrollSheet implements FromCollection, WithHeadings
{
    protected $periode;

    public function __construct($periode)
    {
        $this->periode = $periode;
    }

    public function collection()
    {
        // Ambil data payroll berdasarkan periode yang diberikan
        return Payrollns::with('employee', 'payrolinfo')
            ->where('periode', $this->periode)
            ->get()
            ->map(function ($item, $key) {
                return [
                    'No' => $key + 1,
                    'Transaction ID' => now()->format('dmy') . str_pad($key + 1, 2, '0', STR_PAD_LEFT),
                    'Transfer Type' => '',
                    'Debited Acc.' => '5380888132',
                    'Beneficiary ID' => '',
                    'Credited Acc.' => optional($item->payrolinfo)->bank_number,
                    'Amount' => $item->thp,
                    'Eff. Date' => '',
                    'Transaction Purpose' => '',
                    'Currency' => '',
                    'Charges Type' => '',
                    'Charges Acc.' => '',
                    'Remark 1' => '',
                    'Remark 2' => '',
                    'Receiver Bank Cd' => '',
                    'Receiver Bank Name' => 'BCA',
                    'Receiver Name' => optional($item->employee)->nama,
                    'Receiver Cust. Type' => '',
                    'Receiver Cust. Residen' => '',
                    'Transaction Cd' => '',
                    'Beneficiary Email' => '',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'No', 
            'Transaction ID', 
            'Transfer Type', 
            'Debited Acc.', 
            'Beneficiary ID', 
            'Credited Acc.', 
            'Amount', 
            'Eff. Date', 
            'Transaction Purpose', 
            'Currency', 
            'Charges Type', 
            'Charges Acc.', 
            'Remark 1', 
            'Remark 2', 
            'Receiver Bank Cd', 
            'Receiver Bank Name', 
            'Receiver Name', 
            'Receiver Cust. Type', 
            'Receiver Cust. Residen', 
            'Transaction Cd', 
            'Beneficiary Email'
        ];
    }


}
