@extends('layout.master')
@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="https://cdn.datatables.net/fixedcolumns/4.2.2/css/fixedColumns.bootstrap5.min.css" rel="stylesheet"/>
@endpush

@section('content')
    <div class="container">
        <h1>Duplicate Absens Records</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('absens.index') }}" method="GET">
            <div class="form-group mb-2">
                <label for="start_date">Start Date</label>
                <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $startDate ?? '') }}" required>
            </div>
            <div class="form-group mb-2">
                <label for="end_date">End Date</label>
                <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $endDate ?? '') }}" required>
            </div>
            <button type="submit" class="btn btn-primary mb-2">Check Duplicates</button>
        </form>

        @if (!empty($duplicates) && !$duplicates->isEmpty())
            <form id="bulkDeleteForm" action="{{ route('absens.bulkDeleteDuplicates2') }}" method="POST">
                @csrf
                <input type="hidden" name="start_date" value="{{ $startDate }}">
                <input type="hidden" name="end_date" value="{{ $endDate }}">

                <div class="table-responsive">
                    <table id="dataTableExample" class="table mt-3">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="selectAll"></th>
                                <th>NIK</th>
                                <th>Name</th>
                                <th>Date</th>
                                <th>Count</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($duplicates as $duplicate)
                                <tr>
                                    <td><input type="checkbox" name="duplicates[]" value="{{ $duplicate->nik }}|{{ $duplicate->tanggal }}"></td>
                                    <td>{{ $duplicate->nik }}</td>
                                    <td>{{ $duplicate->nama }}</td>
                                    <td>{{ $duplicate->tanggal }}</td>
                                    <td>{{ $duplicate->count }}</td>
                                    <td>
                                        <form action="{{ route('absens.deleteDuplicate', ['nik' => $duplicate->nik, 'tanggal' => $duplicate->tanggal]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="start_date" value="{{ $startDate }}">
                                            <input type="hidden" name="end_date" value="{{ $endDate }}">
                                            <button type="submit" class="btn btn-danger">Delete Duplicates</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <button type="submit" class="btn btn-danger mt-3">Delete Selected</button>
            </form>
        @elseif (isset($duplicates))
            <p>No duplicate records found for the selected date range.</p>
        @endif
    </div>
@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/data-table.js') }}"></script>
  <script>
    document.getElementById('selectAll').addEventListener('click', function() {
        var checkboxes = document.querySelectorAll('input[type="checkbox"]');
        for (var checkbox of checkboxes) {
            checkbox.checked = this.checked;
        }
    });
  </script>
@endpush