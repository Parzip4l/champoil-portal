<?php

namespace App\Exports;

use App\Absen;
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

    public function __construct($unitBisnis, $loggedInUserNik, $startDate, $endDate)
    {
        $this->unitBisnis = $unitBisnis;
        $this->loggedInUserNik = $loggedInUserNik;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Mengambil semua karyawan dalam unit bisnis tertentu
        $employees = Employee::where('unit_bisnis', $this->unitBisnis)
        ->where(function ($query) {
            $query->where('organisasi', 'Management Leaders');
        })
        ->get();

        // Mengatur struktur data untuk menyimpan hasil
        $result = collect();

        foreach ($employees as $employee) {
            $absensi = Absen::where('nik', $employee->nik)
                ->whereBetween('tanggal', [$this->startDate, $this->endDate])
                ->select('tanggal', 'clock_in', 'clock_out', 'status')
                ->get();

            foreach ($this->getDateRange($this->startDate, $this->endDate) as $date) {
                $absenHarian = $absensi->where('tanggal', $date->toDateString())->first();

                $result->push([
                    'Nama Karyawan' => $employee->nama,
                    'Tanggal' => $date->toDateString(),
                    'Clock In' => $absenHarian ? $absenHarian->clock_in : '',
                    'Clock Out' => $absenHarian ? $absenHarian->clock_out : '',
                    'Status' => $absenHarian ? $absenHarian->status : '',
                ]);
            }
        }

        return $result;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // Specify the headers for your exported data
        return [
            'Nama Karyawan',
            'Tanggal',
            'Clock In',
            'Clock Out',
            'Status',
        ];
    }

    /**
     * Get date range between two dates
     *
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return array
     */
    protected function getDateRange($startDate, $endDate)
    {
        $dates = [];
        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            $dates[] = $currentDate->copy();
            $currentDate->addDay();
        }

        return $dates;
    }
}
