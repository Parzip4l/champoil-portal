<?php

namespace App\Exports;

use App\Absen;
use App\ModelCG\Schedule;
use App\Employee;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AttendenceExport implements FromCollection, WithHeadings
{
    protected $unitBisnis;
    protected $loggedInUserNik;
    protected $startDate;
    protected $endDate;
    protected $dates;
    protected $organisasi;

    public function __construct($unitBisnis, $loggedInUserNik, $startDate, $endDate, $organisasi = null)
    {
        $this->unitBisnis = $unitBisnis;
        $this->loggedInUserNik = $loggedInUserNik;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->organisasi = $organisasi;

        $this->dates = collect();
        $current = $startDate->copy();
        while ($current->lte($endDate)) {
            $this->dates->push($current->copy());
            $current->addDay();
        }
    }

    public function headings(): array
    {
        $headings = ['Nama Karyawan'];
        foreach ($this->dates as $date) {
            $headings[] = $date->format('d M');
        }
        $headings[] = 'Total Masuk';
        $headings[] = 'Tidak Masuk';
        return $headings;
    }

    public function collection()
    {
        $employeesQuery = Employee::where('unit_bisnis', $this->unitBisnis)
            ->where('resign_status', '0');

        if ($this->organisasi) {
            $employeesQuery->where('organisasi', $this->organisasi);
        }

        $employees = $employeesQuery->get();
        logger('Organisasi Filter: ' . $this->organisasi);

        $data = [];

        foreach ($employees as $employee) {
            $row = [$employee->nama];
            $absens = Absen::where('nik', $employee->nik)
                ->whereBetween('tanggal', [$this->startDate->format('Y-m-d'), $this->endDate->format('Y-m-d')])
                ->get()
                ->keyBy('tanggal');

            $schedules = Schedule::where('employee', $employee->nik)
                    ->whereBetween('tanggal', [$this->startDate->format('Y-m-d'), $this->endDate->format('Y-m-d')])
                    ->get()
                    ->keyBy('tanggal');

            $totalMasuk = 0;
            $totalTidakMasuk = 0;

            foreach ($this->dates as $date) {
                $absen = $absens[$date->toDateString()] ?? null;
                $schedule = $schedules[$date->toDateString()] ?? null;

                if ($absen && $absen->clock_in) {
                    $row[] = ($absen->clock_in ?? '-') . ' / ' . ($absen->clock_out ?? '-') . '( '.($schedule->shift ?? '-').' )';
                    $totalMasuk++;
                } else {
                    $row[] = '-'.' / '.'- ( '.($schedule->shift ?? '-').' )';
                    $totalTidakMasuk++;
                }
            }

            // Tambahkan kolom total di akhir baris
            $row[] = $totalMasuk;
            $row[] = $totalTidakMasuk;

            $data[] = $row;
        }

        return new Collection($data);
    }

    protected function generateDateRange(Carbon $start, Carbon $end)
    {
        $dates = [];
        $current = $start->copy();
        while ($current->lte($end)) {
            $dates[] = $current->copy();
            $current->addDay();
        }
        return $dates;
    }
}
