{{-- filepath: c:\laragon\www\arik_web_portals\resources\views\frontend\l_and_d_training_courses\create.blade.php --}}
@extends('frontend.layouts.app')

@section('title', 'Add New Training Course')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Add New Training Course</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('frontend.l_and_d_training_courses.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" name="title" id="title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="is_virtual">Is Virtual</label>
                            <select name="is_virtual" id="is_virtual" class="form-control">
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="in_house">In House</label>
                            <select name="in_house" id="in_house" class="form-control">
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="facilitated_by_in_house">Facilitated By In House</label>
                            <select name="facilitated_by_in_house" id="facilitated_by_in_house" class="form-control">
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="facilitated_by">Facilitated By</label>
                            <input type="text" name="facilitated_by" id="facilitated_by" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="venue">Venue</label>
                            <input type="text" name="venue" id="venue" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="held_from">Held From</label>
                            <input type="datetime-local" name="held_from" id="held_from" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="ended_at">Ended At</label>
                            <input type="datetime-local" name="ended_at" id="ended_at" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="certificate_name">Certificate Name</label>
                            <input type="text" name="certificate_name" id="certificate_name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="cost_in_naira">Cost in Naira</label>
                            <input type="text" name="cost_in_naira" id="cost_in_naira" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="cost_in_dollars">Cost in Dollars</label>
                            <input type="text" name="cost_in_dollars" id="cost_in_dollars" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                        <a href="{{ route('frontend.l_and_d_training_courses.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection