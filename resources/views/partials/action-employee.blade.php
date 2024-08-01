<div class="dropdown">
<button class="btn btn-link p-0 dropdown-toggle" type="button" id="customDropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="icon-lg text-muted pb-3px" data-feather="more-horizontal"></i>
    </button>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <a class="dropdown-item d-flex align-items-center" href="{{ route('employee.edit', ['employee' => $data->nik]) }}">
            <i data-feather="git-branch" class="icon-sm me-2"></i> <span class="">Edit</span>
        </a>
        <a class="dropdown-item d-flex align-items-center" href="{{ route('employee.show', $data->id) }}">
            <i data-feather="eye" class="icon-sm me-2"></i> <span class="">View Detail</span>
        </a>
        <a href="#" class="dropdown-item d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#resign-{{ $data->id }}">
            <i data-feather="user-x" class="icon-sm me-2"></i> <span class="">Resign</span>
        </a>
        <form action="#" method="POST" id="delete_contact" class="contactdelete">
            @csrf @method('DELETE')
            <a class="dropdown-item d-flex align-items-center" href="#" onClick="showDeleteDataDialog('{{ $data->id }}')">
                <i data-feather="trash" class="icon-sm me-2"></i>
                <span class="">Delete</span>
            </a>
        </form>
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
