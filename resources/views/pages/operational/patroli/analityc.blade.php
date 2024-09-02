@extends('layout.master')
<style>
  /* styles.css */
.loading-backdrop {
    display: none; /* Initially hidden */
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
    z-index: 9999; /* High z-index to ensure it covers other elements */
    align-items: center;
    justify-content: center;
    display: flex;
}

.loading-spinner {
    text-align: center;
    padding: 20px;
    font-size: 18px;
    color: #fff; /* White text color */
    border: 4px solid rgba(255, 255, 255, 0.3); /* Light border */
    border-radius: 50%;
    border-top: 4px solid #fff; /* White top border for spinner effect */
    width: 50px;
    height: 50px;
    animation: spin 1s linear infinite; /* Spin animation */
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
@section('content')
<div id="loadingBackdrop" class="loading-backdrop">
  <div class="loading-spinner"></div>
</div>
<div class="row">
<div class="col-md-8">
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
                            <div class="col-md-4 desktop mb-4">
                                <div class="card custom-card2">
                                    <div class="card-body">
                                        <div class="title-card">
                                            <h6>Job Applicant</h6>
                                        </div>
                                        <div class="count mt-2">
                                            <h4 id="total_applicant">3873</h4>
                                        </div>
                                    </div>
                                    <div class="card-footer d-flex" id="applicant"><img src="https://hris.truest.co.id/images/male.png" style="width:15px">3486 (90% )<img src="https://hris.truest.co.id/images/female.png" style="width:15px">387 (10% )</div>
                                </div>
                            </div>
                            <div class="col-md-4 desktop mb-4">
                                <div class="card custom-card2">
                                    <div class="card-body">
                                        <div class="title-card">
                                            <h6>Qualicatioins</h6>
                                        </div>
                                        <div class="count mt-2">
                                            <h4 id="total_qualifikasi">2534 (65.4%)</h4>
                                        </div>
                                    </div>
                                    <div class="card-footer d-flex" id="qualification">
                                    <img src="https://hris.truest.co.id/images/male.png" style="width:15px"> 2309 (91.1% )<img src="https://hris.truest.co.id/images/female.png" style="width:15px"> 225 (8.9% )</div>
                                </div>
                            </div>
                            <div class="col-md-4 desktop mb-4">
                                <div class="card custom-card2">
                                    <div class="card-body">
                                        <div class="title-card">
                                            <h6>IQ Test</h6>
                                        </div>
                                        <div class="count mt-2">
                                            <h4 id="total_iq">1648 (65%)</h4>
                                        </div>
                                    </div>
                                    <div class="card-footer d-flex" id="iq_test"><img src="https://hris.truest.co.id/images/male.png" style="width:15px"> 1493 (90.6% )<img src="https://hris.truest.co.id/images/female.png" style="width:15px"> 155 (9.4% )</div>
                                </div>
                            </div>

                            <div class="col-md-4 desktop mb-4">
                                <div class="card custom-card2">
                                    <div class="card-body">
                                        <div class="title-card">
                                            <h6>EQ Test</h6>
                                        </div>
                                        <div class="count mt-2">
                                            <h4 id="total_eq">1188 (72.1%)</h4>
                                        </div>
                                    </div>
                                    <div class="card-footer d-flex" id="eq_test"><img src="https://hris.truest.co.id/images/male.png" style="width:15px"> 1072 (90.2% )<img src="https://hris.truest.co.id/images/female.png" style="width:15px"> 116 (9.8% )</div>
                                </div>
                            </div>
                            <div class="col-md-4 desktop mb-4">
                                <div class="card custom-card2">
                                    <div class="card-body">
                                        <div class="title-card">
                                            <h6>Value Test</h6>
                                        </div>
                                        <div class="count mt-2">
                                            <h4 id="total_value">2073 (174.5%)</h4>
                                        </div>
                                    </div>
                                    <div class="card-footer d-flex" id="value_test">
                                    <img src="https://hris.truest.co.id/images/male.png" style="width:15px"> 1895 (91.4% )<img src="https://hris.truest.co.id/images/female.png" style="width:15px"> 178 (8.6% )</div>
                                </div>
                            </div>
                            
                            <div class="col-md-4 desktop mb-4">
                                <div class="card custom-card2">
                                    <div class="card-body">
                                        <div class="title-card">
                                            <h6>Applicant To Training</h6>
                                        </div>
                                        <div class="count mt-2">
                                            <h4 id="total_app_to_training">362 (9.3%)</h4>
                                        </div>
                                    </div>
                                    <div class="card-footer d-flex" id="app_training">
                                    <img src="https://hris.truest.co.id/images/male.png" style="width:15px"> undefined (NaN% )<img src="https://hris.truest.co.id/images/female.png" style="width:15px"> undefined (NaN% )</div>
                                </div>
                            </div>
                        
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/fullcalendar/index.global.min.js') }}"></script>
  <!-- <script src="{{ asset('assets/js/fullcalendar.js') }}"></script> -->
@endpush

@push('custom-scripts')
  
<script>

const loadingBackdrop = document.getElementById('loadingBackdrop');

// Function to show the loading backdrop
function showLoading() {
    loadingBackdrop.style.display = 'flex';
}

// Function to hide the loading backdrop
function hideLoading() {
    loadingBackdrop.style.display = 'none';
}

hideLoading()

</script>


@endpush