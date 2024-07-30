<div class="dropdown custom-dropdown">
    <button class="btn btn-link p-0 dropdown-toggle" type="button" id="customDropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
    </button>
    <div class="dropdown-menu" aria-labelledby="customDropdownMenuButton">
        <a class="dropdown-item d-flex align-items-center" href="#" onclick="viewInvoice('{{ $row->id }}'); return false;">
            <i data-feather="eye" class="icon-sm me-2"></i> <span>View</span>
        </a>
        <a class="dropdown-item d-flex align-items-center" href="#" onclick="printInvoice('{{ $row->id }}'); return false;">
            <i data-feather="printer" class="icon-sm me-2"></i> <span>Print</span>
        </a>
    </div>
</div>

@push('custom-scripts')
<script src="{{ asset('assets/plugins/feather-icons/feather.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        feather.replace();  // Initialize Feather icons
    });

    function viewInvoice(id) {
        // Redirect to the invoice view page
        window.location.href = '/invoices/' + id;
    }

    function printInvoice(id) {
        // Open a new window for printing
        window.open('/invoices/' + id + '/print', '_blank');
    }
</script>
@endpush

@push('plugin-styles')
<link href="{{ asset('css/custom-dropdown.css') }}" rel="stylesheet" />
@endpush
