@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="card-title">Profit and Loss</h6>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="dataTableExample" class="table">
                        <tbody>
                            <tr>
                                <td>
                                    <span class="toggle-income badge bg-success" style="cursor: pointer;">
                                        Income
                                        <i class="me-2 icon-md toggle-icon" data-feather="chevron-down"></i>
                                    </span>
                                </td>
                                <td class="text-right"></td>
                            </tr>
                            @foreach ($incomeAccounts as $incomeAccount)
                            <tr class="income-details" style="display: none;">
                                <div class="accordion-content">
                                    <td>{{$incomeAccount->name}}</td>
                                    <td class="text-right">Rp. {{ number_format($incomeAccount->total_debit, 0, ',', '.') }}</td>
                                </div>
                            </tr>
                            @endforeach
                            <tr>
                                <td>Net Profit</td>
                                <td class="text-right">200000</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('custom-scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleIncome = document.querySelector('.toggle-income');
        const incomeDetails = document.querySelector('.income-details');
        const toggleIcon = document.querySelector('.toggle-icon');

        toggleIncome.addEventListener('click', function() {
            incomeDetails.style.display = incomeDetails.style.display === 'none' ? 'table-row' : 'none';
            toggleIcon.setAttribute('data-feather', incomeDetails.style.display === 'none' ? 'chevron-down' : 'chevron-up');
            feather.replace();
        });
    });
</script>
@endpush
