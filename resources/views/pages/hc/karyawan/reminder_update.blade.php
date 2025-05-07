@extends('layout.master2')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/jquery-tags-input/jquery.tagsinput.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/dropzone/dropzone.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/dropify/css/dropify.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/pickr/themes/classic.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
@endpush

@php 

@endphp 

@section('content')
<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Update Personal Data</h4>
        </div>
        <div class="card-body">
            <form id="reminderForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="id" value="{{ request()->segment(count(request()->segments())) }}">
                <div class="row">
                    <div class="col-md-6">
                        <div class="col">
                            <label class="form-label">Sertifikasi</label>
                            <select class="form-select" data-width="100%" name="sertifikasi" id="sertifikasi" required="">
                                <option value="TIDAK ADA">TIDAK ADA</option>
                                <option value="GADA PRATAMA">GADA PRATAMA</option>
                                <option value="GADA MADYA">GADA MADYA</option>
                                <option value="GADA UTAMA">GADA UTAMA</option>
                                <option value="LAINNYA">LAINNYA</option>
                            </select>
                        </div>
                        <div class="form-group d-none" id="sertifikasiFields">
                            <label for="sertifikasi_file">Upload Sertifikasi File</label>
                            <input type="file" class="form-control" id="sertifikasi_file" name="sertifikasi_file" accept="image/*">
                            <button type="button" class="btn btn-primary d-none" id="submitButton">Check Sertifikasi</button><br/>
                            <label for="sertifikasi_expired_date" class="mt-3">Expired Date Sertifikasi</label>
                            <input type="text" class="form-control" id="sertifikasi_expired_date" name="sertifikasi_expired_date" readonly>
                        </div>
                        
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" >
                        </div>
                        <div class="form-group">
                            <label for="nomor_telepon_pribadi">Nomor Telepon Pribadi (terhubung ke WhatsApp)</label>
                            <input type="text" class="form-control" id="nomor_telepon_pribadi" name="nomor_telepon_pribadi" >
                        </div>
                        <div class="form-group">
                            <label for="nomor_telepon_darurat">Nomor Telepon Darurat (terhubung ke WhatsApp)</label>
                            <input type="text" class="form-control" id="nomor_telepon_darurat" name="nomor_telepon_darurat" >
                        </div>
                        <div class="form-group">
                            <label for="alamat_domisili">Alamat Domisili</label>
                            <textarea class="form-control" id="alamat_domisili" name="alamat_domisili" rows="3" ></textarea>
                        </div>
                        <div class="form-group">
                            <label for="pendidikan">Pendidikan</label>
                            <select class=" form-select" data-width="100%" name="pendidikan" required="">
                                <option value="SD">SD</option>
                                <option value="SMP">SMP</option>
                                <option value="SMA">SMA</option>
                                <option value="DIPLOMA">DIPLOMA</option>
                                <option value="SARJANA">SARJANA</option>
                                <option value="MAGISTER">MAGISTER</option>
                                <option value="DOKTOR">DOKTOR</option>
                                <option value="OTHERS">OTHERS</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tinggi_badan">Tinggi Badan (cm)</label>
                            <input type="number" class="form-control" id="tinggi_badan" name="tinggi_badan">
                        </div>
                        <div class="form-group">
                            <label for="berat_badan">Berat Badan (kg)</label>
                            <input type="number" class="form-control" id="berat_badan" name="berat_badan">
                        </div>
                        <div class="form-group">
                            <label for="golongan_darah">Golongan Darah</label>
                            <select class="form-control" id="golongan_darah" name="golongan_darah">
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="AB">AB</option>
                                <option value="O">O</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="foto_biru">Foto Background Berlatar Biru</label>
                            <input type="file" class="form-control" id="foto_biru" name="foto_biru" accept="image/*">
                        </div>
                    </div>
                    <div class="col-md-6">
                        
                        <div class="form-group">
                            <label for="status_pernikahan">Status Pernikahan</label>
                            <select class="form-control" id="status_pernikahan" name="status_pernikahan" >
                                <option value="Single">Single</option>
                                <option value="Married">Married</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="jumlah_tanggungan">Jumlah Tanggungan</label>
                            <input type="number" class="form-control" id="jumlah_tanggungan" name="jumlah_tanggungan" >
                        </div>
                        <div class="form-group">
                            <label for="bpjs_kesehatan">BPJS Ketenaga Kerjaan</label>
                            <input type="text" class="form-control" id="bpjs_kesehatan" name="bpjs_kesehatan" >
                        </div>
                        <div class="form-group">
                            <label for="npwp">NPWP</label>
                            <input type="text" class="form-control" id="npwp" name="npwp" >
                        </div>
                        <div class="form-group">
                            <label for="bank_name">Bank Name (BNI)</label>
                            <input type="text" class="form-control" id="bank_name" name="bank_name" value="BNI" readonly>
                        </div>
                        <div class="form-group">
                            <label for="nomor_rekening">Nomor Rekening (BNI)</label>
                            <input type="text" class="form-control" id="nomor_rekening" name="nomor_rekening" >
                        </div>
                        
                    </div>
                </div>
                <div class="text-center mt-4">
                    <button type="button" class="btn btn-primary" id="submitForm">Submit Form</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/inputmask/jquery.inputmask.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/typeahead-js/typeahead.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/jquery-tags-input/jquery.tagsinput.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/dropzone/dropzone.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/dropify/js/dropify.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/pickr/pickr.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/flatpickr/flatpickr.min.js') }}"></script>
@endpush

@push('custom-scripts')
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    $(document).ready(function() {
        $('#sertifikasi').on('change', function() {
            const selectedValue = $(this).val();
            if (selectedValue === 'GADA PRATAMA' || selectedValue === 'GADA MADYA' || selectedValue === 'GADA UTAMA') {
                $('#sertifikasiFields').removeClass('d-none');
                $('#submitButton').removeClass('d-none'); // Show the button
            } else {
                $('#sertifikasiFields').addClass('d-none');
                $('#submitButton').addClass('d-none'); // Hide the button
                $('#sertifikasi_file').val('');
                $('#sertifikasi_expired_date').val('');
            }
        });

        $('#submitButton').on('click', function() {
            let form = $('#reminderForm');
            let formData = new FormData(form[0]);
            let fileInput = $('#sertifikasi_file');
            let file = fileInput[0].files[0];

            if (file) {
                console.log('File detected:', file); // Debugging log
                let ocrFormData = new FormData();
                ocrFormData.append('image', file);
                ocrFormData.append('document_type', 'kta');

                console.log('OCR FormData:', Array.from(ocrFormData.entries())); // Debugging log

                Swal.fire({
                    title: 'Checking FILE...',
                    text: 'Please wait while we verify the document.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                axios.post('https://data.cityservice.co.id/ocr/public/api/v1/cek-ocr', ocrFormData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(function(ocrResponse) {
                    Swal.close();
                    if (!ocrResponse.data.error) {
                        const result = ocrResponse.data.result;
                        console.log('OCR Result:', result); // Debugging log
                        $('#nama').val(result.nama);
                        $('#alamat_domisili').val(result.alamat);
                        $('#sertifikasi_expired_date').val(result.berlaku_sd);

                    } else {
                        Swal.fire('Error', 'OCR verification failed. Please check the document.', 'error');
                    }
                })
                .catch(function(error) {
                    Swal.close();
                    console.error('OCR Error:', error.response.data); // Debugging log
                    Swal.fire('Error', 'An error occurred during OCR verification.', 'error');
                });
            }
        });

        $('#submitForm').on('click', function() {
            let form = $('#reminderForm');
            let formData = new FormData(form[0]);

            console.log('Submitting form to /api/v1/submit-employe-update'); // Debugging log

            submitForm('/api/v1/submit-employe-update', formData);
        });

        function submitOcr(actionUrl, formData) {
            Swal.fire({
                title: 'Submitting...',
                text: 'Please wait while we process your request.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            axios.post(actionUrl, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(function(response) {
                Swal.close();
                Swal.fire('Success', 'Form submitted successfully!', 'success');
            })
            .catch(function(error) {
                Swal.close();
                Swal.fire('Error', 'An error occurred while submitting the form.', 'error');
                console.error('Form submission error:', error.response.data); // Debugging log
            });
        }

        function submitForm(actionUrl, formData) {
            Swal.fire({
                title: 'Submitting...',
                text: 'Please wait while we process your request.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            axios.post(actionUrl, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(function(response) {
                Swal.close();
                Swal.fire('Success', 'Form submitted successfully!', 'success');
            })
            .catch(function(error) {
                Swal.close();
                Swal.fire('Error', 'An error occurred while submitting the form.', 'error');
                console.error('Form submission error:', error.response.data); // Debugging log
            });
        }
    });
  </script>
@endpush