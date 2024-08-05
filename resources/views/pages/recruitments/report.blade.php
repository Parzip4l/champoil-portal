@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
  <style>
    .backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }

    .spinner {
        width: 50px;
        height: 50px;
        border: 5px solid #f3f3f3;
        border-top: 5px solid #3498db;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
  </style>
@endpush

@section('content')
<div id="loading-backdrop" class="backdrop">
    <div class="spinner"></div>
</div>
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5 class="mb-0 align-self-center">Recruitments Analytics</h5>
                <form method="get">
                    <div class="row">
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="tanggal" id="daterange_picker">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="accordion mb-4" id="accordionExample">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    Show Card Data
                </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <div class="row">
                            <div class="col-md-3 desktop mb-4">
                                <div class="card custom-card2">
                                    <div class="card-body">
                                        <div class="title-card">
                                            <h6>Job Applicant</h6>
                                        </div>
                                        <div class="count mt-2">
                                            <h2 id="total_applicant"></h2>
                                        </div>
                                    </div>
                                    <div class="card-footer d-flex" id="applicant">
                                        
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 desktop mb-4">
                                <div class="card custom-card2">
                                    <div class="card-body">
                                        <div class="title-card">
                                            <h6>Qualicatioins</h6>
                                        </div>
                                        <div class="count mt-2">
                                            <h2 id="total_qualifikasi"></h2>
                                        </div>
                                    </div>
                                    <div class="card-footer d-flex" id="qualification">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 desktop mb-4">
                                <div class="card custom-card2">
                                    <div class="card-body">
                                        <div class="title-card">
                                            <h6>IQ Test</h6>
                                        </div>
                                        <div class="count mt-2">
                                            <h2 id="total_iq"></h2>
                                        </div>
                                    </div>
                                    <div class="card-footer d-flex" id="iq_test">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 desktop mb-4">
                                <div class="card custom-card2">
                                    <div class="card-body">
                                        <div class="title-card">
                                            <h6>EQ Test</h6>
                                        </div>
                                        <div class="count mt-2">
                                            <h2 id="total_eq"></h2>
                                        </div>
                                    </div>
                                    <div class="card-footer d-flex" id="eq_test">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 desktop mb-4">
                                <div class="card custom-card2">
                                    <div class="card-body">
                                        <div class="title-card">
                                            <h6>Value Test</h6>
                                        </div>
                                        <div class="count mt-2">
                                            <h2 id="total_value"></h2>
                                        </div>
                                    </div>
                                    <div class="card-footer d-flex" id="value_test">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 desktop mb-4">
                                <div class="card custom-card2">
                                    <div class="card-body">
                                        <div class="title-card">
                                            <h6>Medical Test</h6>
                                        </div>
                                        <div class="count mt-2">
                                            <h2 id="total_medic"></h2>
                                        </div>
                                    </div>
                                    <div class="card-footer d-flex" id="medic_test">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 desktop mb-4">
                                <div class="card custom-card2">
                                    <div class="card-body">
                                        <div class="title-card">
                                            <h6>Training</h6>
                                        </div>
                                        <div class="count mt-2">
                                            <h2 id="total_training"></h2>
                                        </div>
                                    </div>
                                    <div class="card-footer d-flex" id="training">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 desktop mb-4">
                                <div class="card custom-card2">
                                    <div class="card-body">
                                        <div class="title-card">
                                            <h6>Applicant To Training</h6>
                                        </div>
                                        <div class="count mt-2">
                                            <h2 id="total_app_to_training"></h2>
                                        </div>
                                    </div>
                                    <div class="card-footer d-flex" id="app_training">
                                    </div>
                                </div>
                            </div>
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="accordion mb-4" id="accordionExample">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" 
                        type="button" 
                        data-bs-toggle="collapse" 
                        data-bs-target="#kebutuhan-project" 
                        aria-expanded="true" 
                        aria-controls="collapseOne">
                    GRAFIK KEBUTUHAN PROJECT
                </button>
                </h2>
                <div id="kebutuhan-project" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#kebutuhan-project">
                    <div class="accordion-body">
                        <div id="turnOver" width="400" height="100%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="accordion mb-4" id="accordionExample">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" 
                        type="button" 
                        data-bs-toggle="collapse" 
                        data-bs-target="#resign" 
                        aria-expanded="true" 
                        aria-controls="collapseOne">
                    {{ strtoupper('resigned in less than 30 days') }}
                </button>
                </h2>
                <div id="resign" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#resign">
                <div class="accordion-body">
                    <table class="table" id="dataTableExample">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>NAMA</th>
                            <th>NIK</th>
                            <th>TGL JOIN</th>
                            <th>TGL RESIGN</th>
                            <th>PROJECT TERAKHIR</th>
                            <th>JML HARI</th>
                          </tr>
                        </thead>
                        <tbody>
                            @if($resign30days)
                                @foreach($resign30days as $row)
                                    <tr>
                                        <td></td>
                                        <td>{{ $row->nama }}</td>
                                        <td>{{ $row->ktp }}</td>
                                        <td>{{ date('d F Y',strtotime($row->join_date)) }}</td>
                                        <td>{{ date('d F Y',strtotime($row->created_at)) }}</td>
                                        <td>{{ $row->project }}</td>
                                        <td></td>
                                    </tr>
                                @endforeach
                            @endif
                            
                          
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
        </div>
        <div class="accordion mb-4" id="accordionExample">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" 
                        type="button" 
                        data-bs-toggle="collapse" 
                        data-bs-target="#resign" 
                        aria-expanded="true" 
                        aria-controls="collapseOne">
                    {{ strtoupper('turn over') }}
                </button>
                </h2>
                <div id="resign" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#resign">
                    <div class="accordion-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="chart-widget mb-2">
                                    <div id="percentage"></div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="list-group list-group-flush my-n3">
                                    <div class="list-group-item" style="border-color:#fff !important;">
                                        <div class="row align-items-center">
                                            <div class="col-md-6">
                                                <div class="col">
                                                    <strong>Permintaan Freelance</strong>
                                                </div>
                                                <div class="col-auto">
                                                    <span id="permintaan_bko"></span> People Power
                                                    <div class="progress mt-2" style="height: 4px;">
                                                    <div class="progress-bar" role="progressbar" style="width: 85%" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="col">
                                                    <strong>Melekat</strong>
                                                </div>
                                                <div class="col-auto">
                                                    <span id="melekat"></span> People Power
                                                    <div class="progress mt-2" style="height: 4px;">
                                                    <div class="progress-bar" role="progressbar" style="width: 85%" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="col">
                                                    <strong>Mutasi Project</strong>
                                                </div>
                                                <div class="col-auto">
                                                    <span id="mutasi_project"></span> People Power
                                                    <div class="progress mt-2" style="height: 4px;">
                                                    <div class="progress-bar" role="progressbar" style="width: 85%" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="col">
                                                    <strong>Resign Dadakan</strong>
                                                </div>
                                                <div class="col-auto">
                                                    <span id="resign_dadakan"></span> People Power
                                                    <div class="progress mt-2" style="height: 4px;">
                                                    <div class="progress-bar" role="progressbar" style="width: 85%" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="col">
                                                    <strong>CUT Operational</strong>
                                                </div>
                                                <div class="col-auto">
                                                    <span id="cut_oprasional"></span> People Power
                                                    <div class="progress mt-2" style="height: 4px;">
                                                    <div class="progress-bar" role="progressbar" style="width: 85%" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="col">
                                                    <strong>OMN</strong>
                                                </div>
                                                <div class="col-auto">
                                                    <span id="omn"></span> People Power
                                                    <div class="progress mt-2" style="height: 4px;">
                                                    <div class="progress-bar" role="progressbar" style="width: 85%" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="col">
                                                    <strong>Pengembalian Anggota</strong>
                                                </div>
                                                <div class="col-auto">
                                                    <span id="pengembalian_anggota"></span> People Power
                                                    <div class="progress mt-2" style="height: 4px;">
                                                    <div class="progress-bar" role="progressbar" style="width: 85%" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="accordion mb-4" id="accordionExample">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" 
                        type="button" 
                        data-bs-toggle="collapse" 
                        data-bs-target="#resign" 
                        aria-expanded="true" 
                        aria-controls="collapseOne">
                    {{ strtoupper('Grafik source applicant') }}
                </button>
                </h2>
                <div id="resign" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#resign">
                    <div class="accordion-body">
                        <div id="data_source" width="400" height="500"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="accordion mb-4" id="accordionExample">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" 
                        type="button" 
                        data-bs-toggle="collapse" 
                        data-bs-target="#resign" 
                        aria-expanded="true" 
                        aria-controls="collapseOne">
                    {{ strtoupper('Last 5 weeks chart') }}
                </button>
                </h2>
                <div id="resign" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#resign">
                    <div class="accordion-body">
                        <div id="turnOver10" width="400" height="100%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="accordion mb-4" id="accordionExample">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" 
                        type="button" 
                        data-bs-toggle="collapse" 
                        data-bs-target="#resign" 
                        aria-expanded="true" 
                        aria-controls="collapseOne">
                    {{ strtoupper('Training ratio for the last 5 months') }}
                </button>
                </h2>
                <div id="resign" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#resign">
                <div class="accordion-body">
                    <div id="lima_bulan" width="400" height="500"></div>
                </div>
                </div>
            </div>
        </div>
        <div class="accordion mb-4" id="accordionExample">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" 
                        type="button" 
                        data-bs-toggle="collapse" 
                        data-bs-target="#resign" 
                        aria-expanded="true" 
                        aria-controls="collapseOne">
                    {{ strtoupper('Client request') }}
                </button>
                </h2>
                <div id="resign" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#resign">
                <div class="accordion-body">
                    <table class="table permintaan_client" id="dataTableExample">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>NAME</th>
                            <th>NIK</th>
                            <th>DOKUMEN</th>
                            <th>CLIENT NAME</th>
                          </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- End -->
@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
  <script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
  
@endpush

@push('custom-scripts')
<script src="{{ asset('assets/js/data-table.js') }}"></script>
<script src="{{ asset('assets/js/sweet-alert.js') }}"></script>
<script>
    $('#loading-backdrop').show();
    axios.get('https://data.cityservice.co.id/cs/public/api/card-data', {
        params: {
            tanggal:"{{ @$_GET['tanggal'] }}"
        }
    })
    .then(function (response) {
        $('#applicant').empty();
        $('#total_applicant').empty();
        $('#total_qualifikasi').empty();
        $('#iq_test').empty();
        $('#total_iq').empty();
        $('#eq_test').empty();
        $('#total_eq').empty();

        function calculatePercentage(part, total) {
            return Math.round((part / total) * 1000) / 10; // Round to 1 decimal place
        }

        var pelamar = response.data.pelamar;
        var total_pelamar = pelamar.pelamar_pria + pelamar.pelamar_wanita;


        $('#applicant').append(
                '<img src="' + '{{ asset("images/male.png") }}' + '" style="width:20px">' + pelamar.pelamar_pria + ' (' + calculatePercentage(pelamar.pelamar_pria, total_pelamar) + '% )' +
                '<img src="' + '{{ asset("images/female.png") }}' + '" style="width:20px">' + pelamar.pelamar_wanita + ' (' + calculatePercentage(pelamar.pelamar_wanita, total_pelamar) + '% )'
            );

        $('#total_applicant').text(total_pelamar);

        var qualifikasi = response.data.kualifikasi;
        var medical = response.data.medis;
        var iq_total = qualifikasi.iq_lolos_pria + qualifikasi.iq_lolos_wanita;
       

        $('#qualification').append(
            '<img src="' + '{{ asset("images/male.png") }}' + '" style="width:20px"> ' + qualifikasi.lolos_pria + ' (' + calculatePercentage(qualifikasi.lolos_pria, qualifikasi.total_lolos) + '% )' +
            '<img src="' + '{{ asset("images/female.png") }}' + '" style="width:20px"> ' + qualifikasi.lolos_wanita + ' (' + calculatePercentage(qualifikasi.lolos_wanita, qualifikasi.total_lolos) + '% )'
        );

        $('#total_qualifikasi').text(qualifikasi.total_lolos + ' (' + calculatePercentage(qualifikasi.total_lolos, total_pelamar) + '%)');

        $('#iq_test').append(
            '<img src="' + '{{ asset("images/male.png") }}' + '" style="width:20px"> ' + qualifikasi.iq_lolos_pria + ' (' + calculatePercentage(qualifikasi.iq_lolos_pria, iq_total) + '% )' +
            '<img src="' + '{{ asset("images/female.png") }}' + '" style="width:20px"> ' + qualifikasi.iq_lolos_wanita + ' (' + calculatePercentage(qualifikasi.iq_lolos_wanita,iq_total) + '% )'
        );

        $('#total_iq').text(iq_total + ' (' + calculatePercentage(iq_total, qualifikasi.total_lolos) + '%)');

        var eq_total = qualifikasi.eq_lolos_pria + qualifikasi.eq_lolos_wanita;

        $('#eq_test').append(
            '<img src="' + '{{ asset("images/male.png") }}' + '" style="width:20px"> ' + qualifikasi.eq_lolos_pria + ' (' + calculatePercentage(qualifikasi.eq_lolos_pria, eq_total) + '% )' +
            '<img src="' + '{{ asset("images/female.png") }}' + '" style="width:20px"> ' + qualifikasi.eq_lolos_wanita + ' (' + calculatePercentage(qualifikasi.eq_lolos_wanita,eq_total) + '% )'
        );

        $('#total_eq').text(eq_total + ' (' + calculatePercentage(eq_total, iq_total) + '%)');

        var total_value = qualifikasi.tech_lolos_pria + qualifikasi.tech_lolos_wanita;

        $('#value_test').append(
            '<img src="' + '{{ asset("images/male.png") }}' + '" style="width:20px"> ' + qualifikasi.tech_lolos_pria + ' (' + calculatePercentage(qualifikasi.tech_lolos_pria, total_value) + '% )' +
            '<img src="' + '{{ asset("images/female.png") }}' + '" style="width:20px"> ' + qualifikasi.tech_lolos_wanita + ' (' + calculatePercentage(qualifikasi.tech_lolos_wanita,total_value) + '% )'
        );
        $('#total_value').text(total_value + ' (' + calculatePercentage(total_value, eq_total) + '%)');

        $('#medic_test').append(
            '<img src="' + '{{ asset("images/male.png") }}' + '" style="width:20px"> ' + medical.lolos_pria + ' (' + calculatePercentage(medical.lolos_pria, medical.total_lolos) + '% )' +
            '<img src="' + '{{ asset("images/female.png") }}' + '" style="width:20px"> ' + medical.lolos_wanita + ' (' + calculatePercentage(medical.lolos_wanita,medical.total_lolos) + '% )'
        );
        $('#total_medic').text(medical.total_lolos + ' (' + calculatePercentage(medical.total_lolos, total_value) + '%)');

        $('#training').append(
            '<img src="' + '{{ asset("images/male.png") }}' + '" style="width:20px"> ' + qualifikasi.training_lolos_pria + ' (' + calculatePercentage(qualifikasi.training_lolos_pria, (qualifikasi.training_lolos_pria + qualifikasi.training_lolos_wanita)) + '% )' +
            '<img src="' + '{{ asset("images/female.png") }}' + '" style="width:20px"> ' + qualifikasi.training_lolos_wanita + ' (' + calculatePercentage(qualifikasi.training_lolos_wanita,(qualifikasi.training_lolos_pria + qualifikasi.training_lolos_wanita)) + '% )'
        );
        $('#total_training').text((qualifikasi.training_lolos_pria + qualifikasi.training_lolos_wanita) + ' (' + calculatePercentage((qualifikasi.training_lolos_pria + qualifikasi.training_lolos_wanita), medical.total_lolos) + '%)');

        $('#app_training').append(
            '<img src="' + '{{ asset("images/male.png") }}' + '" style="width:20px"> ' + qualifikasi.training_lolos_pria + ' (' + calculatePercentage(qualifikasi.training_lolos_pria,total_pelamar) + '% )' +
            '<img src="' + '{{ asset("images/female.png") }}' + '" style="width:20px"> ' + qualifikasi.training_lolos_wanita + ' (' + calculatePercentage(qualifikasi.training_lolos_wanita,total_pelamar) + '% )'
        );
        $('#total_app_to_training').text((qualifikasi.training_lolos_pria + qualifikasi.training_lolos_wanita) + ' (' + calculatePercentage((qualifikasi.training_lolos_pria + qualifikasi.training_lolos_wanita),total_pelamar) + '%)');
        
        var turnOverData = response.data.chart_turn_over;
        var colors = {
            primary        : "#6571ff",
            secondary      : "#7987a1",
            success        : "#05a34a",
            info           : "#66d1d1",
            warning        : "#fbbc06",
            danger         : "#ff3366",
            light          : "#e9ecef",
            dark           : "#060c17",
            muted          : "#7987a1",
            gridBorder     : "rgba(77, 138, 240, .15)",
            bodyColor      : "#000",
            cardBg         : "#fff"
        };
        var fontFamily = "'Roboto', Helvetica, sans-serif";
        const categories = moment.months().slice(0, 12);
        const seriesData = [
            {
                name: 'Permintaan Freelance',
                data: turnOverData.Permintaan_BKO
            },
            {
                name: 'Melekat',
                data: turnOverData.Melekat
            },
            {
                name: 'Mutasi Project',
                data: turnOverData.Mutasi_Project
            },
            {
                name: 'Resign dadakan',
                data: turnOverData.Resign_dadakan
            },
            {
                name: 'Cut Oprasional',
                data: turnOverData.Cut_Oprasional
            },
            {
                name: 'OMN',
                data: turnOverData.OMN
            },
            {
                name: 'Pengembalian Anggota',
                data: turnOverData.Pengembalian_Anggota
            }
        ];
        var barChartOptions = {
            chart: {
                type: 'bar',
                height: 400,
                parentHeightOffset: 0,
                toolbar: {
                    show: false
                }
            },
            theme: {
                mode: 'light'
            },
            tooltip: {
                theme: 'light'
            },
            colors: ['#FF6384', '#FF9F40', '#FFCD56', '#4BC0C0', '#36A2EB', '#9966FF', '#C9C9C9'],
            grid: {
                padding: {
                    bottom: -4
                },
                borderColor: '#e0e0e0',
                xaxis: {
                    lines: {
                        show: true
                    }
                }
            },
            series: seriesData,
            xaxis: {
                categories: categories,
                lines: {
                    show: true
                },
                axisBorder: {
                    color: '#e0e0e0'
                },
                axisTicks: {
                    color: '#e0e0e0'
                },
                crosshairs: {
                    stroke: {
                        color: '#bfbfbf'
                    }
                }
            },
            yaxis: {
                title: {
                    text: 'Values',
                    style: {
                        size: 9,
                        color: '#bfbfbf'
                    }
                },
                tickAmount: 4,
                tooltip: {
                    enabled: true
                },
                crosshairs: {
                    stroke: {
                        color: '#bfbfbf'
                    }
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    endingShape: 'rounded'
                }
            },
            dataLabels: {
                enabled: false
            },
            legend: {
                position: 'top',
                horizontalAlign: 'center',
                floating: true,
                offsetY: -20,
                offsetX: -5
            }
        };
        var apexBarChart = new ApexCharts(document.querySelector("#turnOver"), barChartOptions);
        apexBarChart.render();

        var turnOverPercent = response.data.turn_over.percent;
        
        var colors = {
            primary: '#7367F0',
            light: '#e7e7e7',
            muted: '#bfbfbf',
            bodyColor: '#333'
        };

        // ApexCharts configuration
        var options = {
            chart: {
                height: 260,
                type: 'radialBar'
            },
            series: [turnOverPercent],
            colors: [colors.primary],
            plotOptions: {
                radialBar: {
                    hollow: {
                        margin: 15,
                        size: '70%'
                    },
                    track: {
                        show: true,
                        background: colors.light,
                        strokeWidth: '100%',
                        opacity: 1,
                        margin: 5
                    },
                    dataLabels: {
                        showOn: 'always',
                        name: {
                            offsetY: -11,
                            show: true,
                            color: colors.muted,
                            fontSize: '13px'
                        },
                        value: {
                            color: colors.bodyColor,
                            fontSize: '30px',
                            show: true
                        }
                    }
                }
            },
            fill: {
                opacity: 1
            },
            stroke: {
                lineCap: 'round'
            },
            labels: ['Turn Over ( '+response.data.turn_over.total_mp+' / '+response.data.turn_over.bagian+' )']
        };

        // Render the chart
        var chart = new ApexCharts(document.querySelector("#percentage"), options);
        chart.render();   

        $.each(response.data.turn_over.result, function(key, value) {
            $('#'+key).text(value);
        });


        $.each(response.data.permintaan_client, function(key, value) {
            $('.permintaan_client tbody').append(
                '<tr>'+
                '<td></td>'+
                '<td>'+value['nama_lengkap']+'</td>'+
                '<td>'+value['nomor_induk']+'</td>'+
                '<td></td>'+
                '<td>'+value['client_name']+'</td>'+
                '</tr>'
            );
        });


        // Define your data
        var statistik = response.data.statistik;
        let label_key = [];
        let data_values = [];

        $.each(statistik, function(key, value) {
            label_key.push(key.replace(/_/g, ' ')); // Replace underscores with spaces for better readability
            data_values.push(value[0]); // Push the first element of the value array
        });

        // ApexCharts configuration
        var options = {
            chart: {
                type: 'line',
                height: 350,
                toolbar: {
                    show: false
                }
            },
            series: [{
                name: 'Statistik Pelamar',
                data: data_values
            }],
            xaxis: {
                categories: label_key,
            },
            yaxis: {
                title: {
                    text: 'Statistik Pelamar'
                },
                min: 0
            },
            colors: ["rgba(75, 192, 192, 1)"],
            stroke: {
                width: 2,
                curve: 'straight'
            },
            markers: {
                size: 5
            },
            fill: {
                type: 'solid'
            },
            tooltip: {
                enabled: true,
                theme: 'light'
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        height: 300
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };
        // Render the chart
        var chart = new ApexCharts(document.querySelector("#data_source"), options);
        chart.render();

        // Parse the string data to JSON
        var valueTest5 = response.data.five_week.value_test_5;
        var training5 = response.data.five_week.training_5;
        var tanggal = response.data.five_week.tanggal;
        
        // ApexCharts configuration
        var options = {
            chart: {
                type: 'bar',
                height: 350,
                toolbar: {
                    show: false
                }
            },
            series: [
                {
                    name: 'Value Test',
                    data: valueTest5
                },
                {
                    name: 'Training',
                    data: training5
                }
            ],
            xaxis: {
                categories: tanggal
            },
            yaxis: {
                title: {
                    text: 'Count'
                },
                min: 0
            },
            colors: ['#FF6384', '#FF9F40'],
            plotOptions: {
                bar: {
                    horizontal: false,
                    endingShape: 'rounded',
                    columnWidth: '55%'
                }
            },
            dataLabels: {
                enabled: false
            },
            tooltip: {
                theme: 'light'
            },
            grid: {
                borderColor: '#e0e0e0',
                xaxis: {
                    lines: {
                        show: true
                    }
                }
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        height: 300
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };

        // Render the chart
        var chart = new ApexCharts(document.querySelector("#turnOver10"), options);
        chart.render();

        // Assuming response.data.ratio_5_bulan contains the data
        var ratio5Bulan = response.data.ratio_5_bulan;

        var monthNames = {
            "01": "January",
            "02": "February",
            "03": "March",
            "04": "April",
            "05": "May",
            "06": "June",
            "07": "July",
            "08": "August",
            "09": "September",
            "10": "October",
            "11": "November",
            "12": "December"
        };
        // Extract the categories (months) and data series
        var categories_bln = ratio5Bulan.bulan.map(function(month) {
            // Format month here if needed
            return monthNames[month];
        });

        var series = [];
        for (var key in ratio5Bulan) {
            if (key !== 'bulan') {
                series.push({
                    name: key.replace(/_/g, ' '), // Replace underscores with spaces for better readability
                    data: ratio5Bulan[key]
                });
            }
        }

        // ApexCharts configuration
        var options = {
            chart: {
                type: 'line',
                height: 350,
                toolbar: {
                    show: false
                }
            },
            series: series,
            xaxis: {
                categories: categories_bln,
            },
            yaxis: {
                title: {
                    text: 'Values'
                },
                min: 0
            },
            colors: ["#4BC0C0", "#FF6384", "#FFCE56", "purple", "#36A2EB", "#FF9F40","red"],
            stroke: {
                width: 2,
                curve: 'straight'
            },
            markers: {
                size: 5
            },
            fill: {
                type: 'solid'
            },
            tooltip: {
                enabled: true,
                theme: 'light'
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        height: 300
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };

        // Render the chart
        var chart = new ApexCharts(document.querySelector("#lima_bulan"), options);
        chart.render();
    
    
    
    })
    .catch(function (error) {
        console.log(error);
    })
    .finally(function () {
        // always executed
        $('#loading-backdrop').hide();
    });

</script>
<script>
        flatpickr("#daterange_picker", {
            mode: "range",
            dateFormat: "Y-m-d",
            onClose: function(selectedDates, dateStr, instance) {
                console.log(dateStr); // Date range in 'Y-m-d to Y-m-d' format
            }
        });
</script>
@endpush