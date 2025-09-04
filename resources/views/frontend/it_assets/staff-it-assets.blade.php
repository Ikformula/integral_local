@extends('frontend.layouts.app')

@section('title', 'Staff IT Assets' )


@push('after-styles')
    <link rel="stylesheet" href="{{ asset('adminlte3.2/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet"
          href="{{ asset('adminlte3.2/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

@endpush


@section('content')

    <div class="container-fluid">
    <div class="row">

        <div class="col-md-12">

            <div class="card bg-gradient-secondary arik-card animate__animated animate__backInUp">
                <div class="card-header">
                    <h3 class="card-title">
                        Staff Member's Details
                    </h3>
                </div>
                <div class="card-body p-0">
                    @canany (['manage own unit info', 'update other staff info'])
                        <form action="{{ route('frontend.it_assets.staff.it.assets') }}" class="m-3" method="GET">
                            <label>Staff ARA Number</label>
                            <div class="input-group mb-3">
                                <input type="search" name="staff_ara_id" id="staff_ara_id"
                                       value="{{ $_GET['staff_ara_id'] }}" class="form-control">
                                <div class="input-group-append">
                                    <button type="submit" class="btn bg-maroon">Search</button>
                                </div>
                            </div>
                        </form>
                    @endcanany

                    <table class="table">
                        <tbody>
                        <tr>
                            <th>Name:</th>
                            <td>{{ $staff->name }}</td>
                        </tr>

                        <tr>
                            <th>ARA Number:</th>
                            <td>{{ $staff->staff_ara_id }}</td>
                        </tr>

                        <tr>
                            <th>Designation:</th>
                            <td>{{ $staff->job_title }}</td>
                        </tr>
                        <tr>
                            <th>Department:</th>
                            <td>{{ $staff->department_name }}</td>
                        </tr>
                        <tr>
                            <th>Paypoint:</th>
                            <td>{{ $staff->paypoint }}</td>
                        </tr>

                        @if(isset($staff->location))
                            <tr>
                                <th>Location:</th>
                                <td>{{ $staff->location }}</td>
                            </tr>
                        @endif

                        </tbody>
                    </table>

                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4>@yield('title')</h4>
                </div>
                <div class="card-body px-0 pt-0">
                    <table class="table table-striped" id="itAssetsTable">
                        <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Brand</th>
                            <th>Device Type</th>
                            <th>Model</th>
                            <th>Serial Number</th>
                            <th>Action</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($it_assets as $it_asset)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $it_asset->brand }}</td>
                                <td>{{ $it_asset->device_type }}</td>
                                <td>{{ $it_asset->model }} </td>
                                <td>{{ $it_asset->serial_number }}</td>
                                <td>
                                    @include('frontend.it_assets._it-assets-action-buttons')
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
@endsection

@push('after-scripts')
    <script>
        document.querySelector('form').addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = new FormData(e.target);
            const url = '{{ route('frontend.it_assets.store') }}';

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    body: formData,
                });

                if (!response.ok) {
                    throw new Error('Request failed.');
                }

                const data = await response.json();

                if (data.status === 'success') {
                    // Add row to the table
                    const tableBody = document.querySelector('#itAssetsTable tbody');
                    const newRow = document.createElement('tr');
                    newRow.innerHTML = `
          <td>${data.it_asset.serial_number}</td>
          <td>${data.it_asset.brand}</td>
          <td>${data.it_asset.device_type}</td>
          <td>${data.it_asset.model}</td>
          <td>
            <a href="#" class="btn btn-xs btn-primary">View</a>
            <a href="#" class="btn btn-xs btn-secondary">Edit</a>
            <a href="#" class="btn btn-xs btn-danger">Delete</a>
          </td>
        `;
                    tableBody.appendChild(newRow);
                } else {
                    // Handle other statuses if needed
                    console.log('Error: Status is not success.');
                }
            } catch (error) {
                console.error('Error:', error);
            }
        });
    </script>

    <script src="{{ asset('adminlte3.2/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $('.select2').select2({
            theme: 'bootstrap4'
        });
    </script>
@endpush
