{{-- filepath: c:\laragon\www\arik_web_portals\resources\views\frontend\l_and_d_training_courses\edit.blade.php --}}
@extends('frontend.layouts.app')

@section('title', 'Edit Training Course')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Training Course</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('frontend.l_and_d_training_courses.update', $course->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" name="title" id="title" class="form-control" value="{{ $course->title }}" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control">{{ $course->description }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="is_virtual">Is Virtual</label>
                            <select name="is_virtual" id="is_virtual" class="form-control">
                                <option value="1" {{ $course->is_virtual ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ !$course->is_virtual ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="in_house">In House</label>
                            <select name="in_house" id="in_house" class="form-control">
                                <option value="1" {{ $course->in_house ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ !$course->in_house ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="facilitated_by_in_house">Facilitated By In House</label>
                            <select name="facilitated_by_in_house" id="facilitated_by_in_house" class="form-control">
                                <option value="1" {{ $course->facilitated_by_in_house ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ !$course->facilitated_by_in_house ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="facilitated_by">Facilitated By</label>
                            <input type="text" name="facilitated_by" id="facilitated_by" class="form-control" value="{{ $course->facilitated_by }}">
                        </div>
                        <div class="form-group">
                            <label for="venue">Venue</label>
                            <input type="text" name="venue" id="venue" class="form-control" value="{{ $course->venue }}">
                        </div>
                        <div class="form-group">
                            <label for="held_from">Held From</label>
                            <input type="datetime-local" name="held_from" id="held_from" class="form-control" value="{{ $course->held_from }}" required>
                        </div>
                        <div class="form-group">
                            <label for="ended_at">Ended At</label>
                            <input type="datetime-local" name="ended_at" id="ended_at" class="form-control" value="{{ $course->ended_at }}" required>
                        </div>
                        <div class="form-group">
                            <label for="certificate_name">Certificate Name</label>
                            <input type="text" name="certificate_name" id="certificate_name" class="form-control" value="{{ $course->certificate_name }}">
                        </div>
                        <div class="form-group">
                            <label for="cost_in_naira">Cost in Naira</label>
                            <input type="text" name="cost_in_naira" id="cost_in_naira" class="form-control" value="{{ $course->cost_in_naira }}">
                        </div>
                        <div class="form-group">
                            <label for="cost_in_dollars">Cost in Dollars</label>
                            <input type="text" name="cost_in_dollars" id="cost_in_dollars" class="form-control" value="{{ $course->cost_in_dollars }}">
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('frontend.l_and_d_training_courses.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection