@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Duplicate Absens Records</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('absens.index') }}" method="GET">
            <div class="form-group">
                <label for="start_date">Start Date</label>
                <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $startDate ?? '') }}" required>
            </div>
            <div class="form-group">
                <label for="end_date">End Date</label>
                <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $endDate ?? '') }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Check Duplicates</button>
        </form>

        @if (!empty($duplicates) && !$duplicates->isEmpty())
            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
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
        @elseif (isset($duplicates))
            <p>No duplicate records found for the selected date range.</p>
        @endif
    </div>
@endsection
