@extends('layout.master')

@push('plugin-styles')
<link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
<link href="https://cdn.datatables.net/fixedcolumns/4.2.2/css/fixedColumns.bootstrap5.min.css" rel="stylesheet"/>
@endpush

@section('content')
    <h4 class="mb-3">Rekap Absensi</h4>

    {{-- Filter --}}
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card custom-card2">
                <div class="card-body">
                    <form method="GET" class="row g-3 align-items-end">
                        <div class="col-md-2">
                            <label for="month">Bulan</label>
                            <select class="form-select" name="month" id="month">
                                @for ($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ request('month', now()->month) == $m ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create()->month($m)->locale('id')->isoFormat('MMMM') }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="year">Tahun</label>
                            <select class="form-select" name="year" id="year">
                                @for ($y = now()->year - 2; $y <= now()->year + 1; $y++)
                                    <option value="{{ $y }}" {{ request('year', now()->year) == $y ? 'selected' : '' }}>
                                        {{ $y }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        
                        {{-- Filter Lokasi Kerja --}}
                        @if($useMultilocation)
                            <div class="col-md-3">
                                <label for="work_location_id">Lokasi Kerja</label>
                                <select class="form-select select2" name="work_location_id" id="work_location_id">
                                    <option value="">Semua Lokasi</option>
                                    @if (strtoupper($org) === 'KAS') 
                                        @foreach ($project_list as $lokasi)
                                            <option value="{{ $lokasi->id }}" {{ request('work_location_id') == $lokasi->id ? 'selected' : '' }}>
                                                {{ $lokasi->name }}
                                            </option>
                                        @endforeach
                                    @else
                                        @foreach (\App\Company\WorkLocation::where('company_id', $companyId ?? null)->get() as $lokasi)
                                            <option value="{{ $lokasi->id }}" {{ request('work_location_id') == $lokasi->id ? 'selected' : '' }}>
                                                {{ $lokasi->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        @endif
                        
                        {{-- Filter Organisasi --}}
                        <div class="col-md-3">
                            <label for="organisasi_id">Organisasi</label>
                            <select class="form-select select2" name="organisasi_id" id="organisasi_id">
                                <option value="">Semua Organisasi</option>
                                @foreach (\App\Organisasi\Organisasi::where('company', $org)->get() as $organisasi)
                                    <option value="{{ $organisasi->name }}" {{ request('organisasi_id') == $organisasi->name ? 'selected' : '' }}>
                                        {{ $organisasi->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-2">
                            <button class="btn btn-primary w-100" type="submit">Filter</button>
                        </div>
                        <div class="col-md-12">
                            <button type="button" class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#exportModal">
                                Export to Excel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="card custom-card2">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm align-middle text-center" id="attendance-table">
                    <thead class="table-light sticky-top" style="z-index: 1">
                        <tr>
                            <th style="min-width: 150px">Nama</th>
                            @foreach ($dates as $date)
                                <th style="min-width: 100px" class="text-center">
                                    {{ \Carbon\Carbon::parse($date)->format('d M') }}
                                </th>
                            @endforeach
                            <th>PG</th>
                            <th>MD</th>
                            <th>ML</th>
                            <th>OFF</th>
                            <th>Total Masuk</th>
                            <th>Total Tidak Masuk</th>
                            <th>Total Shift</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total_pg = 0;
                            $total_md = 0;
                            $total_ml = 0;
                            $total_off = 0;
                            $total_masuk = 0;
                            $total_tidak_masuk = 0;
                        @endphp
                        @foreach ($employees as $employee)
                            <tr>
                                <td class="text-start fixed-column" style="min-width: 150px">{{ $employee->nama }}</td>
                                @foreach ($dates as $date)
                                    @php
                                        $absen = isset($absens[$employee->nik]) ? $absens[$employee->nik]->firstWhere('tanggal', $date) : null;
                                        $status = $absen->status ?? null;

                                        

                                        if (strtoupper($org) === 'KAS') {
                                            $scheduleKey = $employee->nik . '-' . $date;
                                        }else{
                                            $scheduleKey = $employee->id . '-' . $date;
                                        }
                                        
                                        $scheduleRaw = $schedules[$scheduleKey] ?? null;
                                        if (!$scheduleRaw) {
                                            \Log::warning('Schedule Not Found', ['scheduleKey' => $scheduleKey]);
                                        }
                                        $schedule = $scheduleRaw instanceof \Illuminate\Support\Collection ? $scheduleRaw->first() : $scheduleRaw;

                                        if (strtoupper($org) === 'KAS') {
                                            $shift = null;
                                            if ($schedule && isset($schedule->shift) && isset($schedule->project)) {
                                                // Cari shift berdasarkan project_id dan shift_code
                                                $shift = \App\ModelCG\Datamaster\ProjectShift::where('project_id', $schedule->project)
                                                    ->where('shift_code', $schedule->shift)
                                                    ->first(); // Ambil yang pertama jika ditemukan
                                            }
                                        } else {
                                            $shift = $schedule && isset($shifts[$schedule->shift_id]) ? $shifts[$schedule->shift_id] : null;
                                        }

                                        
                                        
                                    @endphp

                                    @if(!empty($shift))
                                        @php
                                            if ($shift->shift_code == 'PG') {
                                                $total_pg++;
                                            } elseif ($shift->shift_code == 'MD') {
                                                $total_md++;
                                            } elseif ($shift->shift_code == 'ML') {
                                                $total_ml++;
                                            } elseif ($shift->shift_code == 'OFF') {
                                                $total_off++;
                                            } elseif ($shift->shift_code == 'Masuk') {
                                                $total_masuk++;
                                            } elseif ($shift->shift_code == 'Tidak Masuk') {
                                                $total_tidak_masuk++;
                                            }
                                        @endphp
                                    @endif
                                    <td class="p-1">
                                    @if ($schedule || $shift)
                                        
                                        <p class="text-muted">{{ $shift->shift_code ?? $shift->code ?? 'OFF' }} <br>
                                        (<span class="text-success">{{ $absen->clock_in ?? '-' }}</span> - <span class="text-danger">{{ $absen->clock_out ?? '-' }}</span> - <span class="text-danger">{{ $absen->status ?? '-' }}</span>)</p>
                                            
                                    @else
                                    <p class="text-muted">
                                    <span class="text-success">{{ $absen->clock_in ?? '-' }}</span> - <span class="text-danger">{{ $absen->clock_out ?? '-' }}</span> - <span class="text-danger">{{ $absen->status ?? '-' }}</span></p>
                                    @endif

                                </td>

                                @endforeach
                                <td>{{ $total_pg }}</td>
                                <td>{{ $total_md }}</td>
                                <td>{{ $total_ml }}</td>
                                <td>{{ $total_off }}</td>
                                <td>{{ $total_masuk }}</td>
                                <td>{{ $total_tidak_masuk }}</td>
                                <td>
                                    @php
                                        $scheduleCount = isset($schedule) && $schedule ? (is_countable($schedule) ? count($schedule) : 1) : 0;
                                    @endphp
                                    {{ $scheduleCount > 0 ? $scheduleCount : '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exportModalLabel">Pilih Bulan untuk Export</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form untuk memilih bulan -->
                    <form action="{{ route('export.attendence') }}" method="GET">
                        @csrf
                        <div class="mb-3">
                            <label for="selected_month" class="form-label">Pilih Bulan</label>
                            <input type="month" name="selected_month" id="selected_month" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="organisasi" class="form-label">Pilih Organisasi</label>
                            <select class="form-select" name="organisasi" id="organisasi_id">
                                <option value="">Semua Organisasi</option>
                                @foreach (\App\Organisasi\Organisasi::where('company', $org)->get() as $organisasi)
                                    <option value="{{ $organisasi->name }}" {{ request('organisasi_id') == $organisasi->name ? 'selected' : '' }}>
                                        {{ $organisasi->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Export</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
@endsection

@push('plugin-scripts')
<script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@push('custom-scripts')
<script src="https://cdn.datatables.net/fixedcolumns/4.2.2/js/dataTables.fixedColumns.min.js"></script>
<style>
    .fixed-column {
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);  /* Shadow pada kolom fixed */
        background-color: #f8f9fa; /* Beri latar belakang agar lebih terlihat */
        z-index: 10; /* Pastikan kolom fixed tampil di atas kolom lainnya */
        border-right: 2px solid #dee2e6; /* Border di sebelah kanan kolom */
    }
</style>
<script>
    $(document).ready(function() {
        var table = $('#attendance-table').DataTable({
            scrollX: true,  // Aktifkan scroll horizontal
            fixedColumns: {
                left: 1  // Pinned kolom pertama
            }
        });
    });
</script>
@endpush
