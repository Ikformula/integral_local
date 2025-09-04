@extends('frontend.layouts.app')

@section('title','TROs' )

@section('content')
    <section class="content">
        <div class="container-fluid">

            <h5>Pending Bookings Per Location</h5>
            <div class="row">
                @foreach($stats as $stat)
                    <div class="col-md-3">
                        @component('frontend.components.dashboard_stat_widget', ['icon' => $stat['icon'], 'title' => $stat['title']])
                            {{ $stat['value'] }}
                        @endcomponent
                    </div>
                @endforeach
            </div>


            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            Tour Operation TROs
                        </div>
                        <div class="card-body table-responsive" style="max-height: 350px;">
                            <table class="table table-striped" id="tros">
                                <thead>
                                <tr>
                                    <td>S/N</td>
                                    <td>Name/Staff ARA ID</td>
                                    <td>Location Assigned</td>
                                    <td colspan="2">Action</td>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($location_operators as $operator)
                                    @if($operator->staff_member())
                                    <tr>
                                        <form action="{{ route('frontend.tour_operations.tros.update', $operator) }}" method="POST">
                                            @method('PATCH')
                                            @csrf
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $operator->user->full_name }} - {{ $operator->staff_member()->staff_ara_id }} </td>
                                        <td>
                                            <select class="form-control" name="location" required>
                                                <option selected>{{ $operator->location }}</option>
                                                @foreach($locations as $location)
                                                    @if($location != $operator->location)
                                                    <option>{{ $location }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><button type="submit" class="btn bg-navy">Update</button></td>
                                        </form>
                                        <td>
                                            <form action="{{ route('frontend.tour_operations.tros.destroy', $operator) }}" method="POST">
                                                @method('DELETE')
                                                @csrf
                                                <button type="submit" class="btn bg-maroon">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                                <tr>
                                    <form action="{{ route('frontend.tour_operations.tros.store') }}" method="POST">
                                        @csrf
                                    <td>Add New</td>
                                    <td>
                                        <select class="form-control" name="user_id" required>
                                            <option disabled selected>Select a staff member</option>
                                            @foreach($tros as $tro)
                                                @if($tro->staff_member)
                                                <option value="{{ $tro->id }}">{{ $tro->full_name }}, {{ $tro->staff_member->staff_ara_id }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control" name="location" required>
                                            <option disabled selected>Select a location</option>
                                            @foreach($locations as $location)
                                            <option>{{ $location }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td colspan="2">
                                        <button type="submit" class="btn bg-navy">Assign</button>
                                    </td>
                                    </form>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
