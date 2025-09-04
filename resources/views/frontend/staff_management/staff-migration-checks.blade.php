@extends('frontend.layouts.app')

@section('title', 'Staff With No Email' )

@push('after-styles')
    <style>
        .fixTableHead {
            overflow-y: auto;
            height: 1100px;
        }

        .fixTableHead thead {
            z-index: 999;
        }

        .fixTableHead thead th {
            position: sticky;
            top: 0;
            background-color: #fff;
        }
        </style>

    @include('includes.partials._datatables-css')
    @endpush

@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card arik-card shadow">
                        <div class="card-header">
                            <strong>MS Exchange Users</strong>
                        </div>
                        <div class="card-body p-0 fixTableHead">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Display Name</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                    <th>Licences</th>
                                    <th>Staff ARA ID</th>
                                    <th>Department</th>
                                    <th>Job Title</th>
                                    <th>Outlook</th>
                                    <th>Onedrive</th>
                                    <th>Teams</th>
                                    <th>Calendar</th>
                                    <th>Shared drive</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($ms_users as $ms_user)

                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                      <div class="card-header">
                        MS users
                      </div>
                      <div class="card-body">
                          <table class="table table-striped" id="ms-users">
                              <thead>
                                  <tr>
                                      <th>Display Name</th>
                                      <th>First Name</th>
                                      <th>Last Name</th>
                                      <th>Email</th>
                                  </tr>
                              </thead>
                              <tbody>
                              @foreach($ms_users as $ms_user)
                                  <tr>
                                      <td>{{ $ms_user->display_name }}</td>
                                      <td>{{ $ms_user->first_name }}</td>
                                      <td>{{ $ms_user->last_name }}</td>
                                      <td>{{ $ms_user->email }}</td>
                                  </tr>
                              @endforeach
                              </tbody>
                          </table>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('after-scripts')
    <script>
            function submitEmail(staff_ara_id){
                document.getElementById('btn-' + staff_ara_id).innerHTML = 'Sending...';
                const emailForm = document.getElementById('form-' + staff_ara_id);
                const formData = new FormData(emailForm);
                // console.table(formData);
            fetch("{{ route('storeMsEmail') }}", {
                method: "POST",
                // headers: {
                //     "Content-Type": "application/json"
                // },
                body: formData
            })
                .then(function(response){
                    console.log(response);
                    return response.json();
                })
                .then(data => {
                    if(data.status == 'success'){
                        showInstantToast(data.message);
                        $('#tr-' + staff_ara_id).hide();
                    }
                    console.log(data);
                })
                .catch(console.error);
        }

    </script>
    @include('includes.partials._datatables-js')
    <script>
        $("#ms-users").DataTable({
            "responsive": false,
            "lengthChange": false,
            "autoWidth": false,
            paging: false,
            scrollY: 465,
            "buttons": ["colvis"]
        }).buttons().container().appendTo('#tickets_wrapper .col-md-6:eq(0)');

    </script>
@endpush
