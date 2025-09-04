@extends('frontend.layouts.app')

@section('title', 'View Year\'s Report')

@push('after-styles')
    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
@endpush

@section('content')
    <div class="container mt-4">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="card">
                      <div class="card-body">
                          <form action="{{ route('frontend.safety_review.years.report') }}" method="POST" target="_blank" enctype="multipart/form-data">
                              @csrf
                              <h5>Prepared and Reviewed by:</h5>
                              <div class="form-group">
                                  <label for="prepared_by_">Name</label>
                                  <input type="text" name="prepared_by_name" id="" class="form-control" value="Friday Uwanni">
                              </div>
                              <div class="form-group">
                                  <label for="prepared_by_">Designation</label>
                                  <input type="text" name="prepared_by_designation" id="" class="form-control" value="Safety Superintendent">
                              </div>
                              <div class="form-group">
                                  <label for="prepared_by_sign">Sign</label>
                                  <input type="file" name="prepared_by_sign" id="prepared_by_sign" class="form-control" value="">
                              </div>
                              <div class="form-group">
                                  <label for="prepared_by_date">Date</label>
                                  <input type="text" name="prepared_by_date" id="prepared_by_date" class="form-control" value="{{ now()->toFormattedDayDateString() }}">
                              </div>
                              <hr>


                              <h5>Approved by:</h5>
                              <div class="form-group">
                                  <label for="approved_by_">Name</label>
                                  <input type="text" name="approved_by_name" id="" class="form-control" value="Captain Jide Bakare">
                              </div>
                              <div class="form-group">
                                  <label for="approved_by_">Designation</label>
                                  <input type="text" name="approved_by_designation" id="" class="form-control" value="Safety Director">
                              </div>
                              <div class="form-group">
                                  <label for="approved_by_sign">Sign</label>
                                  <input type="file" name="approved_by_sign" id="approved_by_sign" class="form-control" value="">
                              </div>
                              <div class="form-group">
                                  <label for="approved_by_date">Date</label>
                                  <input type="text" name="approved_by_date" id="approved_by_date" class="form-control" value="{{ now()->toFormattedDayDateString() }}">
                              </div>

                              <div class="form-group">
                                  <label>Select Year</label>
                                  <select name="year" class="form-control">
                                      @for($year = now()->year; $year >= 2024; $year--)
<option>{{ $year }}</option>
                                      @endfor
                                  </select>
                              </div>

                              <div class="form-group">
                                  <label for="corrective_action_plan">Corrective Action Plan</label>
                                  <textarea class="form-control" name="corrective_action_plan" id="corrective_action_plan" rows="3" ></textarea>
                              </div>

                              <button type="submit"
                                     class="btn btn-primary"
                                  >View Report</button>

                          </form>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('after-scripts')
    <script>
        // Initialize CKEditor
        ClassicEditor
            .create(document.getElementById('corrective_action_plan'))
            .catch(error => {
                console.error(error);
            });
    </script>
@endpush
