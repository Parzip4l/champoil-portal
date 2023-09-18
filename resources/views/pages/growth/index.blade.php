@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Growth</a></li>
    <li class="breadcrumb-item active" aria-current="page">Growth Team</li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="card-title">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="card-title">Growth Team</h6>
                </div>
                <div class="col-md-6 text-right align-self-center">
                    <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target=".growth">Add A Team</a>
                </div>
            </div>
        </div>
        <div class="table-responsive">
          <table id="dataTableExample" class="table">
            <thead>
              <tr>
                <th>#</th>
                <th>Team Code</th>
                <th>Team Name</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
                @php 
                    $nomor = 1;
                @endphp
                @foreach( $team as $data )
                <tr>
                    <td>{{ $nomor++ }}</td>
                    <td>{{ $data->team_code }}</td>
                    <td>{{ $data->team_name }}</td>
                    <td>
                        <a href="" class="btn btn-sm btn-primary">Details</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Add Team -->
<div class="modal fade bd-example-modal-lg growth" tabindex="-1" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content p-4">
            <h4 class="pb-2">Add Account Manager</h4>
            <hr>
            <form class="forms-sample" action="{{ route('growth.store') }}" method="POST" enctype="multipart/form-data"> 
                @csrf 
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleInputUsername1" class="form-label">Name</label>
                            <input type="text" class="form-control" id="exampleInputUsername1" autocomplete="off" name="team_name" placeholder="Name" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleInputUsername1" class="form-label">Code</label>
                            <input type="text" class="form-control" id="exampleInputUsername1" autocomplete="off" name="team_code" value="{{$generatedCode}}" readonly required>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary me-2 w-100">Save Account Manager</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/data-table.js') }}"></script>
@endpush