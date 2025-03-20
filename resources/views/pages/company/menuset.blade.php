@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="row">
    <div class="col">
        <div class="card custom-card2">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="card-title">Feature List for {{ $company->company_name }}</h4>
                </div>
                <!-- Bulk Action Buttons -->
                <div class="mb-3">
                    <button id="enableSelected" class="btn btn-success">Enable Selected</button>
                    <button id="disableSelected" class="btn btn-danger">Disable Selected</button>
                </div>

                <!-- Data -->
                <input type="hidden" id="company_name" value="{{ $company->company_name }}">
                <div class="table-responsive">
                    <table class="table mb-0" id="featureTable">
                        <thead class="bg-light bg-opacity-50">
                            <tr>
                                <th class="ps-3">
                                    <input type="checkbox" id="selectAll">
                                </th>
                                <th>Feature Name</th>
                                <th>Active</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($features as $feature)
                                @if($feature->parent_id == null)
                                    <!-- Parent Feature -->
                                    <tr class="bg-light">
                                        <td class="ps-3">
                                            <input type="checkbox" class="feature-checkbox" data-feature="{{ $feature->id }}">
                                        </td>
                                        <td>{{ $feature->title }}</td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input toggle-feature" type="checkbox"
                                                    data-company="{{ $company->name }}"
                                                    data-feature="{{ $feature->id }}"
                                                    {{ in_array($feature->id, $enabledFeatures) ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Sub Features -->
                                    @foreach($features->where('parent_id', $feature->id) as $subFeature)
                                        <tr>
                                            <td class="ps-3">
                                                <input type="checkbox" class="feature-checkbox" data-feature="{{ $subFeature->id }}">
                                            </td>
                                            <td class="ps-5">&#8627; {{ $subFeature->title }}</td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input toggle-feature" type="checkbox"
                                                        data-company="{{ $company->name }}"
                                                        data-feature="{{ $subFeature->id }}"
                                                        {{ in_array($subFeature->id, $enabledFeatures) ? 'checked' : '' }}>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
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
  <script>
    $(document).ready(function () {
        // Inisialisasi DataTable
        let table = $('#featureTable').DataTable({
            "paging": false,
            "info": true,
            "ordering": false,
            "searching": true
        });

        // Custom search
        $('#searchFeature').on('keyup', function () {
            table.search(this.value).draw();
        });

        // Toggle feature status
        $(document).on('change', '.toggle-feature', function() {
            let companyId = $(this).data('company');
            let featureId = $(this).data('feature');
            let isEnabled = $(this).is(':checked') ? 1 : 0;

            $.ajax({
                url: "{{ route('company.features.toggle') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    company_id: '{{$company->company_name}}',
                    feature_id: featureId,
                    is_enabled: isEnabled
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to update feature status'
                    });
                }
            });
        });

        // Select All Checkbox
        $('#selectAll').on('change', function() {
            $('.feature-checkbox').prop('checked', $(this).prop('checked'));
        });

        // Bulk Enable/Disable Feature
        function bulkUpdateFeatures(status) {
            let selectedFeatures = $('.feature-checkbox:checked').map(function() {
                return $(this).data('feature'); // Pastikan ini adalah feature_id dari fitur
            }).get();

            let companyName = $('#company_name').val(); // Ambil company_name dari input atau variabel yang tersedia

            if (selectedFeatures.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    text: 'Please select at least one feature.'
                });
                return;
            }

            $.ajax({
                url: "{{ route('company.features.bulkToggle') }}",
                type: "POST",
                data: {
                    company_id: companyName, // Gunakan company_name sebagai company_id
                    feature_ids: selectedFeatures,
                    is_enabled: status,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON.error
                    });
                }
            });
        }


        $('#enableSelected').on('click', function() {
            bulkUpdateFeatures(1);
        });

        $('#disableSelected').on('click', function() {
            bulkUpdateFeatures(0);
        });
    });
  </script>
@endpush
