<?php

namespace App\Exports;

use App\Absen;
use App\Employee;
use App\Payrollns;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AbsensiSheet implements FromCollection, WithHeadings
{
    protected $periode;
    protected $startDate;
    protected $endDate;
    protected $dates;
    protected $niks; // hanya NIK dari payroll

    public function __construct($periode)
    {
        $this->periode = $periode;

        [$start, $end] = explode(' - ', $periode);
        $this->startDate = Carbon::parse($start);
        $this->endDate = Carbon::parse($end);

        // Generate date list
        $this->dates = collect();
        $current = $this->startDate->copy();
        while ($current->lte($this->endDate)) {
            $this->dates->push($current->copy());
            $current->addDay();
        }

        // Ambil NIK dari PayrollNS
        $this->niks = Payrollns::where('periode', $this->periode)
            ->pluck('employee_code')
            ->toArray();
    }

    public function headings(): array
    {
        $headings = ['Nama Karyawan'];
        foreach ($this->dates as $date) {
            $headings[] = $date->format('d-m');
        }
        $headings[] = 'Total Masuk';
        $headings[] = 'Tidak Masuk';
        $headings[] = 'Daily Salary';
        $headings[] = 'Total Lembur';
        $headings[] = 'Lembur Salary / Hours';
        $headings[] = 'Lembur Salary Total';
        $headings[] = 'Uang Kerajinan';
        $headings[] = 'Uang Makan';
        $headings[] = 'Potongan Hutang';
        $headings[] = 'Jumlah Kotor';
        $headings[] = 'Potongan Mess';
        $headings[] = 'Potongan Lain Lain';
        $headings[] = 'THP';
        return $headings;
    }

    public function collection()
    {
        $employees = Employee::whereIn('nik', $this->niks)->get();
        $data = [];

        foreach ($employees as $employee) {
            // Ambil daily salary dari Payrollns
            $payroll = Payrollns::where('employee_code', $employee->nik)
                ->where('periode', $this->periode)
                ->first();

            $dailySalary = $payroll ? $payroll->daily_salary : 0;
            $formattedDailySalary = number_format($dailySalary, 2, ',', '.');
            $totalabsen = $payroll ? $payroll->total_absen : 0;
            $jamLembur = $payroll ? $payroll->jam_lembur : 0;
            $lemburSalary = $payroll ? $payroll->lembur_salary : 0;
            $totalLembur = $payroll ? $payroll->total_lembur : 0;
            $kerajinan = $payroll ? $payroll->uang_kerajinan : 0;
            $makan = $payroll ? $payroll->uang_makan : 0;
            $hutang = $payroll ? $payroll->potongan_hutang : 0;
            $jumlahkotor = $dailySalary * $totalabsen + $totalLembur + $kerajinan + $makan - $hutang;
            $mess = $payroll ? $payroll->potongan_mess : 0;
            $lainlain = $payroll ? $payroll->potongan_lain : 0;
            $thp = $payroll ? $payroll->thp : 0;
            $formatedTHP = number_format($thp, 2, ',', '.');
            $formatedlembur = number_format($totalLembur, 2, ',', '.');
            $formatedmakan = number_format($makan, 2, ',', '.');
            $formatedkerajinan = number_format($kerajinan, 2, ',', '.');
            $formatedhutang = number_format($hutang, 2, ',', '.');
            $formatedkotor = number_format($jumlahkotor, 2, ',', '.');
            $row = [
                $employee->nama,
            ];

            $absens = Absen::where('nik', $employee->nik)
                ->whereBetween('tanggal', [$this->startDate->format('Y-m-d'), $this->endDate->format('Y-m-d')])
                ->get()
                ->keyBy('tanggal');

            $totalMasuk = 0;
            $totalTidakMasuk = 0;

            foreach ($this->dates as $date) {
                $absen = $absens[$date->toDateString()] ?? null;

                if ($absen && $absen->clock_in) {
                    $row[] = ($absen->clock_in ?? '-') . ' / ' . ($absen->clock_out ?? '-');
                    $totalMasuk++;
                } else {
                    $row[] = '-';
                    $totalTidakMasuk++;
                }
            }

            $row[] = $totalMasuk ?? 0;
            $row[] = $totalTidakMasuk;
            $row[] = $formattedDailySalary;
            $row[] = $jamLembur;
            $row[] = $lemburSalary;
            $row[] = $formatedlembur;
            $row[] = $formatedkerajinan;
            $row[] = $formatedmakan;
            $row[] = $formatedhutang;
            $row[] = $formatedkotor;
            $row[] = $mess;
            $row[] = $lainlain;
            $row[] = $formatedTHP;

            $data[] = $row;
        }

        return new Collection($data);
    }
}