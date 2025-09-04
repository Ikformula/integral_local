<!-- resources/views/frontend/ecs_bookings/create.blade.php -->
@extends('frontend.layouts.app')

@section('title', 'Add New Booking')
@push('after-styles')
    <link rel="stylesheet" href="{{ asset('adminlte3.2/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet"
          href="{{ asset('adminlte3.2/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Select Client</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('frontend.ecs_bookings.selectClient') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="client_id">Client</label>
                            <select name="client_id" id="client_id" class="form-control" required>
                                <option value="">-- Select Client --</option>
                                @foreach(App\Models\EcsClient::all() as $client)
                                    <option value="{{ $client->id }}">{{ $client->name_and_balance }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Proceed</button>
                        <a href="{{ route('frontend.ecs_bookings.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('after-scripts')
    <!-- Load CKEditor only if needed -->
    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
    <script>
        document.querySelectorAll('textarea').forEach(function(textarea) {
            if(textarea.id) {
                ClassicEditor.create(textarea).catch(error => { console.error(error); });
            }
        });
    </script>

    <script src="{{ asset('adminlte3.2/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $('.select2').select2({
            theme: 'bootstrap4'
        });
    </script>@endpush
