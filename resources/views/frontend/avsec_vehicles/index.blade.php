<!-- resources/views/frontend/avsec_vehicles/index.blade.php -->
@extends('frontend.layouts.app')

@push('after-styles')
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/searchbuilder/1.7.1/css/searchBuilder.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.5.2/css/dataTables.dateTime.min.css">
<style>
    .thumb-preview {
        max-width: 60px;
        max-height: 60px;
        object-fit: cover;
        border: 1px solid #ccc;
        border-radius: 4px;
    }
</style>
@endpush

@section('title', 'Vehicles List')

@section('content')
<div class="container-fluid">

    @if($logged_in_user->can('manage avsec portals') || $logged_in_user->can('update other staff info'))
    {{-- Statistics for Avsec Vehicles --}}
    @php
    $totalVehicles = $items->count();
    $uniqueStaff = $items->pluck('staff_ara_id')->unique()->count();
    $uniqueBrands = $items->pluck('brand')->unique()->count();
    $uniqueModels = $items->pluck('car_model')->unique()->count();
    $uniqueColours = $items->pluck('colour')->unique()->count();
    $noSticker = $items->whereNull('sticker_number')->count();
    @endphp
    <div class="row mb-2">
        <div class="col-md-2 col-6">
            @component('frontend.components.dashboard_stat_widget-small-box', [
            'title' => 'Total Vehicles',
            'icon' => 'car',
            'colour' => 'primary'
            ])
            {{ $totalVehicles }}
            @endcomponent
        </div>

        <div class="col-md-2 col-6">
            @component('frontend.components.dashboard_stat_widget-small-box', [
            'title' => 'No Sticker',
            'icon' => 'tag',
            'colour' => 'maroon'
            ])
            {{ $noSticker }}
            @endcomponent
        </div>

        <div class="col-md-2 col-6">
            @component('frontend.components.dashboard_stat_widget-small-box', [
            'title' => 'Unique Staff IDs',
            'icon' => 'id-card',
            'colour' => 'secondary'
            ])
            {{ $uniqueStaff }}
            @endcomponent
        </div>
        <div class="col-md-2 col-6">
            @component('frontend.components.dashboard_stat_widget-small-box', [
            'title' => 'Unique Brands',
            'icon' => 'tags',
            'colour' => 'warning'
            ])
            {{ $uniqueBrands }}
            @endcomponent
        </div>
        <div class="col-md-2 col-6">
            @component('frontend.components.dashboard_stat_widget-small-box', [
            'title' => 'Unique Models',
            'icon' => 'cogs',
            'colour' => 'info'
            ])
            {{ $uniqueModels }}
            @endcomponent
        </div>
        <div class="col-md-2 col-6">
            @component('frontend.components.dashboard_stat_widget-small-box', [
            'title' => 'Unique Colours',
            'icon' => 'palette',
            'colour' => 'success'
            ])
            {{ $uniqueColours }}
            @endcomponent
        </div>
    </div>
    @endif

    <div class="row mb-3">
        <div class="col-12">
            <a href="{{ route('frontend.avsec_vehicles.create') }}" class="btn btn-primary">Register a Vehicle</a>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Vehicles List</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered text-nowrap w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Staff</th>
                                <th>Unit</th>
                                <th>Line Manager</th>
                                <th>Registered Name</th>
                                <th>Model</th>
                                <th>Vehicle Type</th>
                                <th>Colour</th>
                                <th>Brand</th>
                                <th>Registration Number</th>
                                <th>Sticker Number</th>
                                <th>Attended By</th>
                                <th>Registration Cert</th>
                                <th>Proof Of Ownership</th>
                                <th>Category</th>
                                <th>Effective</th>
                                <th>Expiry</th>
                                <th>Status</th>
                                <th>Reason</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $key => $item)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $item->staff_ara_idRelation->name_and_ara }}</td>
                                <td>{{ $item->staff_ara_idRelation->department_name }}</td>
                                <td>{{ $item->line_manager_staff_ara_idRelation ? $item->line_manager_staff_ara_idRelation->name_and_ara : '-' }}</td>
                                <td>{{ $item->registered_name_on_vehicle }}</td>
                                <td>{{ $item->car_model }}</td>
                                <td>{{ $item->vehicle_type }}</td>
                                <td>{{ $item->colour }}</td>
                                <td>{{ $item->brand }}</td>
                                <td>{{ $item->reg_number }}</td>
                                <td>{{ $item->sticker_number }}</td>
                                <td>{{ $item->attended_by_user_id }}</td>
                                <td>
                                    @if(!empty($item->registration_cert))
                                    <a href="{{ asset('storage/' . $item->registration_cert) }}"
                                        target="_blank">View</a>
                                    @else
                                    -
                                    @endif
                                </td>
                                <td>
                                    @if(!empty($item->proof_of_ownership))
                                    <a href="{{ asset('storage/' . $item->proof_of_ownership) }}"
                                        target="_blank">View</a>
                                    @else
                                    -
                                    @endif
                                </td>
                                <td>{{ $item->stickerCategory ? $item->stickerCategory->name : '' }}</td>
                                <td>{{ optional($item->effective_date)->toDateString() }}</td>
                                <td>{{ optional($item->expiration_date)->toDateString() }}</td>
                                 @php
                                $colour = 'secondary';
                                if($item->status_symbol() == 'Approved'){
                                $colour = 'success';
                                }else if (!is_null($item->disapproved_at)){
                                $colour = 'danger';
                                }

                                @endphp
                                <td>
                                    <span class="badge badge-{{ $colour }}">{{ $item->status_symbol() }}</span>
                                </td>
                                <td>{{ $item->disapproval_reason }} @if($item->approved_at){{ 'Approved on '.$item->approved_at->toDayDateTimeString () }}@endif</td>
                                <td>
                                    @if($logged_in_user->can('manage avsec portals'))
                                    <form action="{{ route('frontend.avsec_vehicles.approve', $item->id) }}"
                                        method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-sm btn-success" {{ $item->approved_at ? 'disabled' : '' }}>
                                            Approve
                                        </button>
                                    </form>
                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
                                        data-target="#approval-modal-{{ $item->id }}-Id" {{ $item->approved_at ? 'disabled' : '' }}>
                                        Disapprove
                                    </button>
                                    <!-- Modal -->
                                    <div class="modal fade" id="approval-modal-{{ $item->id }}-Id" tabindex="-1"
                                        role="dialog"
                                        aria-labelledby="approval-modal-{{ $item->id }}-TitleId"
                                        aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title"
                                                        id="approval-modal-{{ $item->id }}-TitleId">Submission
                                                        Disapproval</h4>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form method="POST"
                                                    action="{{ route('frontend.avsec_vehicles.disapprove', $item->id) }}">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <p class="text-danger">Disapprove Submission
                                                            by {{ $item->staff_ara_idRelation->name_and_ara }}</p>
                                                        <input type="hidden" name="id" value="{{ $item->id }}">
                                                        <div class="form-group">
                                                            <label for="disapproval_reason_{{ $item->id }}">Reason
                                                                for Disapproval</label>
                                                            <textarea class="form-control"
                                                                name="disapproval_reason"
                                                                id="disapproval_reason_{{ $item->id }}"
                                                                required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Close
                                                        </button>
                                                        <button type="submit" class="btn btn-danger">Submit
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @if($logged_in_user->can('manage avsec portals') && $item->approved_at)
                                    <form action="{{ route('frontend.avsec_vehicles.reopen', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Reopen this approved vehicle for editing?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-info">Reopen</button>
                                    </form>
                                    @endif
                                    {{-- <a href="{{ route('frontend.avsec_vehicles.show', $item->id) }}"--}}
                                    {{-- class="btn btn-sm btn-info">View</a>--}}
                                    <a href="{{ route('frontend.avsec_vehicles.edit', $item->id) }}"
                                        class="btn btn-sm btn-primary">Edit</a>
                                    <form action="{{ route('frontend.avsec_vehicles.destroy', $item->id) }}"
                                        method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
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
    $(document).ready(function() {
        var table = new DataTable('.table', {
            "paging": false,
            scrollY: 465,
            layout: {
                top: {
                    searchBuilder: {}
                },
                topStart: {
                    buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
                }
            }
        });
    });
</script>
@endpush
