@extends('layout.master')

@section('content')
<div class="title mb-3">
    <h4>Employee Details</h4>
</div>
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-tabs nav-tabs-line" id="lineTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-line-tab" data-bs-toggle="tab" data-bs-target="#home" role="tab" aria-controls="home" aria-selected="true">Personal Info</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-line-tab" data-bs-toggle="tab" data-bs-target="#profile" role="tab" aria-controls="profile" aria-selected="false">Attendence</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="contact-line-tab" data-bs-toggle="tab" data-bs-target="#contact" role="tab" aria-controls="contact" aria-selected="false">Emergency Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="document-line-tab" data-bs-toggle="tab" data-bs-target="#document" role="tab" aria-controls="document" aria-selected="false">Document</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="contact-line-tab" data-bs-toggle="tab" data-bs-target="#Payslip" role="tab" aria-controls="Payslip" aria-selected="false">Payslip</a>
                    </li>
                </ul>
                <div class="tab-content mt-3" id="lineTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-line-tab">
                        <!-- Personal Info -->
                    </div>
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-line-tab">
                        <!-- Attendence Info -->
                    </div>
                    <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-line-tab">
                        <!-- Emergency Contact Info -->
                    </div>
                    <div class="tab-pane fade" id="document" role="tabpanel" aria-labelledby="document-line-tab">
                        <!-- Document Info -->
                    </div>
                    <div class="tab-pane fade" id="Payslip" role="tabpanel" aria-labelledby="Payslip-line-tab">
                        <!-- Payslip Info -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('custom-scripts')
<style>
    .nav.nav-tabs.nav-tabs-vertical .nav-link{
        border : 0;
        background-color : transparent;
        padding: 10px 15px;
        margin-bottom: 5px;
    }

    .nav.nav-tabs.nav-tabs-vertical .nav-link.active {
        background-color: #6571ff;
        color: #fff;
        border-radius: 50px;
        font-weight: 500;
    }

    .nav.nav-tabs.nav-tabs-vertical {
        width : 50%;
    }
</style>
@endpush