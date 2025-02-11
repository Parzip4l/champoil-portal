<?php

namespace App\Exports;
use App\Koperasi\Saving;
use App\Koperasi\Anggota;
use App\Employee;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Auth;

class AnggotaKoperasoExport implements FromCollection, WithHeadings
{
    protected $filterStatus;
    protected $loanStatus;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($filterStatus, $loanStatus)
    {
        $this->filterStatus = $filterStatus;
        $this->loanStatus = $loanStatus;
    }

    public function collection()
    {
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();

        $query = Anggota::where('company', $company->unit_bisnis)
                        ->whereNot('member_status', 'review')
                        ->whereNot('member_status', 'reject');

        if ($this->filterStatus !== 'all') {
            $query->where('member_status', $this->filterStatus);
        }

        if ($this->loanStatus !== 'all') {
            $query->where('loan_status', $this->loanStatus);
        }

        $anggota = $query->get();

        // Menambahkan saldo simpanan
        $anggota->each(function ($dataAnggota) {
            $lastSaving = Saving::where('employee_id', $dataAnggota->employee_code)
                ->where('jumlah_simpanan', '!=', 0)
                ->where('totalsimpanan', '!=', 0)
                ->latest('created_at')
                ->first();

            $dataAnggota->saldo_simpanan = $lastSaving ? $lastSaving->totalsimpanan : 0;
        });

        // Pilih hanya kolom yang diperlukan
        return $anggota->map(function ($data) {
            return [
                'Nama'            => $data->nama,
                'NIK'             => $data->employee_code,
                'Tanggal Join'    => $data->join_date,
                'Status Anggota'  => $data->member_status,
                'Status Pinjaman' => $data->loan_status,
                'Saldo Simpanan'  => $data->saldo_simpanan,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama',
            'NIK',
            'Tanggal Join',
            'Status Anggota',
            'Status Pinjaman',
            'Saldo Simpanan'
        ];
    }
}
