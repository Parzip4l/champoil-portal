@extends('layout.master')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card custom-card2">
            <div class="card-header">
                <h6 class="card-title mb-0">Pengaturan Cutoff Payroll</h6>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form method="POST" action="{{ route('payroll.cutoff.update') }}">
                    @csrf
                    @method('PUT')

                    <!-- Pilihan Sama atau Beda -->
                    <div class="form-group mb-2">
                        <label>
                            <input type="radio" name="is_uniform" value="1" {{ optional($setting)->is_uniform ? 'checked' : '' }}> Sama untuk semua
                        </label>
                        <label>
                            <input type="radio" name="is_uniform" value="0" {{ optional($setting)->is_uniform == false ? 'checked' : '' }}> Beda per tipe (Departemen/Organisasi)
                        </label>
                    </div>

                    <!-- Jika Sama untuk Semua -->
                    <div id="uniform-section" style="{{ optional($setting)->is_uniform == false ? 'display:none' : '' }}">
                        <div class="form-group mb-2">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="text" name="start_date" value="{{ $setting->start_date ?? '' }}" class="form-control" placeholder="Tanggal (1-31)">
                        </div>
                        <div class="form-group mb-2">
                            <label class="form-label">Tanggal Akhir</label>
                            <input type="text" name="end_date" value="{{ $setting->end_date ?? '' }}" class="form-control" placeholder="Tanggal (1-31)">
                        </div>
                        <div class="form-group mb-2">
                            <label class="form-label">Tanggal Proses</label>
                            <input type="text" name="process_date" value="{{ $setting->process_date ?? '' }}" class="form-control" placeholder="Tanggal (1-31)">
                        </div>
                    </div>

                    <!-- Jika Beda per Departemen / Organisasi -->
                    <div id="custom-section" style="{{ optional($setting)->is_uniform ? 'display:none' : '' }}">
                        <table class="table table-bordered" id="detail-table">
                            <thead>
                                <tr>
                                    <th>Tipe</th>
                                    <th>Nama</th>
                                    <th>Mulai</th>
                                    <th>Akhir</th>
                                    <th>Proses</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(optional($setting)->details ?? [] as $index => $detail)
                                    <tr>
                                        <td>
                                            <select name="details[{{ $index }}][type]" class="form-control type-select">
                                                <option value="department" {{ $detail->type == 'department' ? 'selected' : '' }}>Organisasi</option>
                                                <option value="organization" {{ $detail->type == 'organization' ? 'selected' : '' }}>Jabatan</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="details[{{ $index }}][ref_id]" class="form-control ref-select">
                                                @if($detail->type == 'department')
                                                    @foreach($departments as $dept)
                                                        <option value="{{ $dept->id }}" {{ $detail->ref_id == $dept->id ? 'selected' : '' }}>
                                                            {{ $dept->name }}
                                                        </option>
                                                    @endforeach
                                                @else
                                                    @foreach($organizations as $org)
                                                        <option value="{{ $org->id }}" {{ $detail->ref_id == $org->id ? 'selected' : '' }}>
                                                            {{ $org->name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </td>
                                        <td><input type="text" name="details[{{ $index }}][start_date]" value="{{ $detail->start_date }}" class="form-control" placeholder="Tanggal (1-31)"></td>
                                        <td><input type="text" name="details[{{ $index }}][end_date]" value="{{ $detail->end_date }}" class="form-control" placeholder="Tanggal (1-31)"></td>
                                        <td><input type="text" name="details[{{ $index }}][process_date]" value="{{ $detail->process_date }}" class="form-control" placeholder="Tanggal (1-31)"></td>

                                        <td><button type="button" class="btn btn-danger remove-row mt-2">🗑</button></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <button type="button" id="add-row" class="btn btn-secondary mt-2">+ Tambah</button>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('input[name="is_uniform"]').forEach((radio) => {
        radio.addEventListener('change', () => {
            const isUniform = radio.value == "1";
            document.getElementById('uniform-section').style.display = isUniform ? 'block' : 'none';
            document.getElementById('custom-section').style.display = isUniform ? 'none' : 'block';
        });
    });

    let index = {{ optional($setting->details)->count() ?? 0 }};
    let departmentOptions = @json($departments->map(fn($dept) => ['id' => $dept->id, 'name' => $dept->name]));
    let organizationOptions = @json($positions->map(fn($org) => ['id' => $org->id, 'name' => $org->name]));

    document.getElementById('add-row').addEventListener('click', function () {
        let typeSelect = `
            <select name="details[${index}][type]" class="form-control type-select">
                <option value="department">Departemen</option>
                <option value="organization">Jabatan</option>
            </select>`;

        let refSelect = `
            <select name="details[${index}][ref_id]" class="form-control ref-select">
                ${departmentOptions.map(dept => `<option value="${dept.id}">${dept.name}</option>`).join('')}
            </select>`;

        const row = `
        <tr>
            <td>${typeSelect}</td>
            <td>${refSelect}</td>
            <td><input type="text" name="details[${index}][start_date]" class="form-control" placeholder="Tanggal (1-31)"></td>
            <td><input type="text" name="details[${index}][end_date]" class="form-control" placeholder="Tanggal (1-31)"></td>
            <td><input type="text" name="details[${index}][process_date]" class="form-control" placeholder="Tanggal (1-31)"></td>
            <td><button type="button" class="btn btn-danger remove-row">🗑</button></td>
        </tr>`;
        
        document.querySelector('#detail-table tbody').insertAdjacentHTML('beforeend', row);
        index++;
    });

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-row')) {
            e.target.closest('tr').remove();
        }
    });

    document.addEventListener('change', function (e) {
        if (e.target.classList.contains('type-select')) {
            let row = e.target.closest('tr');
            let refSelect = row.querySelector('.ref-select');

            if (e.target.value === 'department') {
                refSelect.innerHTML = departmentOptions.map(dept => `<option value="${dept.id}">${dept.name}</option>`).join('');
            } else {
                refSelect.innerHTML = organizationOptions.map(org => `<option value="${org.id}">${org.name}</option>`).join('');
            }
        }
    });
</script>
@endsection
