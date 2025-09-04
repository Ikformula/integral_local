@extends('frontend.layouts.app')

@section('title', 'CUG ' .  \Illuminate\Support\Str::plural('Line', $cugLines->count()))

@push('after-styles')
    @if ($logged_in_user->can('manage own unit info') || $logged_in_user->can('update other staff info'))

    <link rel="stylesheet" href="{{ asset('adminlte3.2/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet"
          href="{{ asset('adminlte3.2/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/searchbuilder/1.7.1/css/searchBuilder.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.5.2/css/dataTables.dateTime.min.css">

        <style>
            .owner-category-group {
                display: none;
            }
        </style>
    @endif

    <style>
        .dob {
            filter: blur(6px);
        }

        .dob:hover {
            filter: blur(0);
            transition: filter 0.4s ease;
        }
    </style>
@endpush
@section('content')
    <div class="container-fluid">
        @if ($logged_in_user->can('manage own unit info') || $logged_in_user->can('update other staff info'))
            <div class="row">
                @foreach($stats as $stat)
                    <div class="col-md-3">
                        @component('frontend.components.dashboard_stat_widget', ['icon' => $stat['icon'], 'title' => $stat['title']])
                            {{ $stat['value'] }}
                        @endcomponent
                    </div>
                @endforeach
            </div>

        <!-- Add New Record -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card collapsed-card">
                    <div class="card-header">
                        <h3 class="card-title">Add New CUG Line</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('frontend.cug_lines.store') }}" method="POST">
                            @csrf
                            <strong>Owner Category</strong><br>
                            <label class="checkbox-inline mr-5">
                                <input type="radio" name="owner_category" id="individual_owner_category" checked value="individual"> Individual
                            </label>

                            <label class="checkbox-inline">
                                <input type="radio" name="owner_category" id="group_owner_category" value="group"> Group
                            </label>

                            <div class="form-group ">
                                <label for="staff_ara_id" class="owner-category-individual">Staff Member</label>
                                <label for="staff_ara_id" class="owner-category-group" style="display: block;">Staff Member in charge</label>
                                <select name="staff_ara_id" id="staff_ara_id" class="form-control">
                                    <option value="" selected>-- Select One --</option>
                                    @foreach ($staffMembers as $staff)
                                        <option value="{{ $staff->staff_ara_id }}">
                                            {{ $staff->surname }} {{ $staff->other_names }} - {{ $staff->email }}, ARA{{ $staff->staff_ara_id }} ({{ $staff->department_name }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group owner-category-group">
                                <label for="surname">Surname</label>
                                <input type="text" name="surname" id="surname" class="form-control">
                            </div>
                            <div class="form-group owner-category-group">
                                <label for="other_names">Other Names</label>
                                <input type="text" name="other_names" id="other_names" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="phone_number">Phone Number</label>
                                <input type="text" name="phone_number" id="phone_number" class="form-control" required>
                            </div>
{{--                            <div class="form-group">--}}
{{--                                <label for="phone_type">Phone Type</label>--}}
{{--                                <select name="phone_type" id="phone_type" class="form-control">--}}
{{--                                    <option value="feature phone">Feature Phone</option>--}}
{{--                                    <option value="smartphone">Smartphone</option>--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                            <div class="form-group">--}}
{{--                                <label for="phone_model">Phone Model</label>--}}
{{--                                <input type="text" name="phone_model" id="phone_model" class="form-control">--}}
{{--                            </div>--}}
                            <button type="submit" class="btn btn-primary">Add</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <!-- Existing Records -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header"><h3 class="card-title">Manage CUG {{ \Illuminate\Support\Str::plural('Line', $cugLines->count()) }}</h3></div>
                    <div class="card-body">
                        <table id="cugLinesTable" class="table table-bordered table-hover table-striped text-nowrap w-100">
                            <thead>
                            <tr>
                                <th>SN</th>
                                <th>Staff ARA ID</th>
                                <th>Surname</th>
                                <th>First name</th>
                                <th>Other Names</th>
                                <th>DOB</th>
                                <th>Category</th>
                                <th>Owner Category</th>
                                <th>Phone Number</th>
                                @if($logged_in_user->can('update other staff info'))
                                <th>Staff Provided Number</th>
                                @endif
{{--                                <th>Phone Type</th>--}}
{{--                                <th>Model</th>--}}
                                <th>Confirmed By</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($cugLines as $key => $cugLine)
                                <tr>
                                    <!-- Serial Number -->
                                    <td>{{ $key + 1 }}</td>

                                    <!-- Staff ARA ID -->
                                    <td>{{ $cugLine->staff_ara_id ? 'ARA'.$cugLine->staff_ara_id : 'N/A' }}</td>

                                    <!-- Surname -->
                                    <td>{{ $cugLine->surname ?? 'N/A' }}</td>

                                    <!-- first name -->
                                    <td>{{ $cugLine->first_name ?? 'N/A' }}</td>

                                    <!-- Other Names -->
                                    <td>{{ $cugLine->other_names ?? 'N/A' }}</td>

                                    <!-- DOB -->
                                    <td class="dob">{{ $cugLine->dob ?? 'N/A' }}</td>

                                    <!-- Category -->
                                    <td>{{ ucfirst($cugLine->category ?? 'N/A') }}</td>

                                    <!-- Owner Category -->
                                    <td>{{ ucfirst($cugLine->owner_category ?? 'N/A') }}</td>

                                    <!-- Phone Number -->
                                    <td>
                                        {!! $cugLine->phone_number ?? '<span class="text-danger">No CUG Line</span>' !!}
                                    </td>

                                @if($logged_in_user->can('update other staff info'))
                                    <!-- Staff Provided Phone Number -->
                                    <td>
                                        {{ $cugLine->user_supplied_phone_number ?? '' }}
                                    </td>
                                @endif
                                    <!-- Phone Type -->
{{--                                    <td>--}}
{{--                                       {{ $cugLine->phone_type ?? '' }}--}}
{{--                                    </td>--}}

{{--                                    <!-- Phone Model -->--}}
{{--                                    <td>--}}
{{--                                        {{ $cugLine->phone_model }}--}}
{{--                                    </td>--}}

                                    <!-- Confirmed By -->
                                    <td>
                                        @if ($cugLine->confirmed_by && $cugLine->confirmedBy)
                                            {{ $cugLine->confirmedBy->full_name }} ({{ $cugLine->confirmedBy->email }})
                                        @elseif(empty($cugLine->phone_number) || !isset($cugLine->phone_number))
                                            <form action="{{ route('frontend.cug_lines.confirm', $cugLine->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm bg-maroon btn-block">Confirm No CUG Line</button>
                                            </form>
                                        @else
                                            <span class="text-muted">Edit to Confirm</span>
                                        @endif
                                    </td>

                                    <!-- Actions -->
                                    <td>
{{--                                        @if ((!empty($cugLine->phone_number) || isset($cugLine->phone_number)) || ($logged_in_user->can('manage own unit info') || $logged_in_user->can('update other staff info')))--}}
                                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#cug-{{ $key }}-Id">Edit</button>
{{--                                        @endif--}}


                                        @if ($logged_in_user->can('manage own unit info') || $logged_in_user->can('update other staff info'))
                                            <form action="{{ route('frontend.cug_lines.destroy', $cugLine->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this record?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        @endif
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

    @foreach ($cugLines as $key => $cugLine)

        <!-- Modal -->
        <div class="modal fade" id="cug-{{ $key }}-Id" tabindex="-1" role="dialog" aria-labelledby="cug-{{ $key }}-TitleId"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="{{ route('frontend.cug_lines.update', $cugLine->id) }}" method="POST">

                <div class="modal-content">
{{--                    <div class="modal-header">--}}
{{--                        <h4 class="modal-title" id="cug-{{ $key }}-TitleId"></h4>--}}
{{--                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
{{--                            <span aria-hidden="true">&times;</span>--}}
{{--                        </button>--}}
{{--                    </div>--}}
                    <div class="modal-body">
                            @csrf
                            <strong>ARA ID: </strong>{{ $cugLine->staff_ara_id ? 'ARA'.$cugLine->staff_ara_id : 'N/A' }}<br>
                        <strong>Name: </strong>{{ $cugLine->surname }} {{ $cugLine->other_names }}<br>
                        <strong>Category: </strong>{{ ucfirst($cugLine->category ?? 'N/A') }}<br>
                        <strong>Owner Category: </strong>{{ ucfirst($cugLine->owner_category ?? 'N/A') }}<br>

{{--                            <div class="form-group mt-3">--}}
{{--                                <label>Phone Type</label>--}}
{{--                            <select name="phone_type" class="form-control">--}}
{{--                                <option value="feature phone" {{ $cugLine->phone_type == 'feature phone' ? 'selected' : '' }}>Feature Phone</option>--}}
{{--                                <option value="smartphone" {{ $cugLine->phone_type == 'smartphone' ? 'selected' : '' }}>Smartphone</option>--}}
{{--                            </select>--}}
{{--                            </div>--}}

                            <div class="form-group">
                                <label>Phone Number</label>
                                <input type="text" name="user_supplied_phone_number" value="{{ $cugLine->phone_number }}" class="form-control" placeholder="Enter Phone Number" required>
                            </div>

{{--                            <div class="form-group">--}}
{{--                                <label>Phone Model</label>--}}
{{--                                <input type="text" name="phone_model" value="{{ $cugLine->phone_model }}" class="form-control" placeholder="Enter Phone Model">--}}
{{--                            </div>--}}

                        <h5>For SIM Porting Purposes</h5>
                        <div class="form-group">
                            <label>Surname</label>
                            <input type="text" name="surname" class="form-control" value="{{ $cugLine->surname }}" required>
                            <span class="text-muted">Kindly fill your name as it was when the SIM was initially registered</span>
                        </div>

                        <div class="form-group">
                            <label>First Name</label>
                            <input type="text" name="first_name" class="form-control" value="{{ $cugLine->first_name }}" required>
                        </div>

                        <div class="form-group">
                            <label>Other Name</label>
                            <input type="text" name="other_names" class="form-control" value="{{ $cugLine->other_names }}">
                        </div>


                        <div class="form-group">
                            <label>Date of Birth</label>
                            <input type="date" name="dob" class="form-control" value="{{ $cugLine->dob }}" required>
                        </div>

{{--                        <div class="form-group">--}}
{{--                            <label>NIN</label>--}}
{{--                            <input type="number" name="nin" class="form-control" minlength="10" maxlength="11" value="{{ $cugLine->nin }}" required>--}}
{{--                        </div>--}}

{{--                        <div class="form-group">--}}
{{--                            <label>SIM Serial Number</label>--}}
{{--                            <input type="text" name="serial_number" class="form-control" value="{{ $cugLine->serial_number }}" required>--}}
{{--                        </div>--}}

{{--                        <div class="form-group">--}}
{{--                            <label>Notes</label>--}}
{{--                            <input type="text" name="notes" maxlength="255" class="form-control form-control-lg" value="{{ $cugLine->notes }}">--}}
{{--                        </div>--}}

                        <div class="form-group">
                            <div class="alert bg-gradient-maroon" role="alert">
                                <strong>NB!</strong>
                                <p>
                                    Upon confirming and saving, you will be redirected to a Microsoft form by MTN to provide some information.
                                    It is necessary that you provide accurate information on that form to enable a seamless porting process.
                                    <br><span class="text-warning"><strong>YOUR NIN IS ONLY COLLECTED ON THE MICROSOFT FORM CREATED & MANAGED BY MTN.<br>ARIK AIR OR IT DEPARTMENT ONLY COLLECT WHAT YOU PROVIDE IN THE ABOVE FORM!.</strong><br><span class="text-warning"><i>Please do not click on the 'Report Abuse' link on the form. Thanks.</i></span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Confirm & Save</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    @endforeach
@endsection

@push('after-scripts')
    @if ($logged_in_user->can('manage own unit info') || $logged_in_user->can('update other staff info'))

    <script src="{{ asset('adminlte3.2/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $('#staff_ara_id').select2({
            theme: 'bootstrap4'
        });
    </script>

    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.dataTables.js"></script>
    <script src="https://cdn.datatables.net/searchbuilder/1.7.1/js/dataTables.searchBuilder.js"></script>
    <script src="https://cdn.datatables.net/searchbuilder/1.7.1/js/searchBuilder.dataTables.js"></script>
    <script src="https://cdn.datatables.net/datetime/1.5.2/js/dataTables.dateTime.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.print.min.js"></script>


    <script>
        $(document).ready(function () {
            var table = new DataTable('.table', {
                "paging": false,
                scrollY: 465,
                layout: {
                    top: {
                        searchBuilder: {
                            // columns: [6],
                            @if(isset($_GET['days_left']))
                            preDefined: {
                                {{--criteria: [--}}
                                {{--    {--}}
                                {{--        data: 'Days Left to End',--}}
                                {{--        condition: '=',--}}
                                {{--        value: [{{ $_GET['days_left'] }}]--}}
                                {{--    }--}}
                                {{--]--}}
                            }
                            @endif
                        }
                    },
                    topStart: {
                        buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
                    }
                }
            });
        });


        document.addEventListener('DOMContentLoaded', function () {
            // Get the radio buttons
            const individualRadio = document.getElementById('individual_owner_category');
            const groupRadio = document.getElementById('group_owner_category');

            // Function to toggle visibility of elements
            function toggleOwnerCategory() {
                const isIndividual = individualRadio.checked;

                // Show/hide elements based on the selected option
                document.querySelectorAll('.owner-category-individual').forEach(el => {
                    el.style.display = isIndividual ? 'block' : 'none';
                });

                document.querySelectorAll('.owner-category-group').forEach(el => {
                    el.style.display = isIndividual ? 'none' : 'block';
                });
            }

            // Attach event listeners to both radio buttons
            individualRadio.addEventListener('change', toggleOwnerCategory);
            groupRadio.addEventListener('change', toggleOwnerCategory);

            // Initial toggle to set the correct visibility on page load
            toggleOwnerCategory();
        });

    </script>
    @endif
@endpush
