{{-- filepath: c:\laragon\www\arik_web_portals\resources\views\frontend\l_and_d_training_courses\show.blade.php --}}
@extends('frontend.layouts.app')

@section('title', 'Training Course Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Training Course Details</h3>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">Title</dt>
                        <dd class="col-sm-8">{{ $course->title }}</dd>

                        <dt class="col-sm-4">Description</dt>
                        <dd class="col-sm-8">{{ $course->description }}</dd>

                        <dt class="col-sm-4">Is Virtual</dt>
                        <dd class="col-sm-8">{{ $course->is_virtual ? 'Yes' : 'No' }}</dd>

                        <dt class="col-sm-4">In House</dt>
                        <dd class="col-sm-8">{{ $course->in_house ? 'Yes' : 'No' }}</dd>

                        <dt class="col-sm-4">Facilitated By In House</dt>
                        <dd class="col-sm-8">{{ $course->facilitated_by_in_house ? 'Yes' : 'No' }}</dd>

                        <dt class="col-sm-4">Facilitated By</dt>
                        <dd class="col-sm-8">{{ $course->facilitated_by }}</dd>

                        <dt class="col-sm-4">Venue</dt>
                        <dd class="col-sm-8">{{ $course->venue }}</dd>

                        <dt class="col-sm-4">Held From</dt>
                        <dd class="col-sm-8">{{ $course->held_from }}</dd>

                        <dt class="col-sm-4">Ended At</dt>
                        <dd class="col-sm-8">{{ $course->ended_at }}</dd>

                        <dt class="col-sm-4">Certificate Name</dt>
                        <dd class="col-sm-8">{{ $course->certificate_name }}</dd>

                        <dt class="col-sm-4">Cost in Naira</dt>
                        <dd class="col-sm-8">{{ isset($course->cost_in_naira) ? number_format($course->cost_in_naira, 2) : '' }}</dd>

                        <dt class="col-sm-4">Cost in Dollars</dt>
                        <dd class="col-sm-8">{{ isset($course->cost_in_dollars) ? number_format($course->cost_in_dollars, 2) : '' }}</dd>
                    </dl>
                    <a href="{{ route('frontend.l_and_d_training_courses.index') }}" class="btn btn-secondary">Back</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
