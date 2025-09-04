@extends('backend.layouts.app')

@section('content')
    <div class="row justify-content-center mb-4">
        <div class="col-md-5">
            <h3>Data Admin Panel - Database Models/Tables</h3>
            <ul class="list-group">

            @foreach($models as $model)
                    <li class="list-group-item"><a href="{{ route('admin.database_admin.show', $model) }}">{{ $model }}</a></li>
                @endforeach
            </ul>
        </div>
    </div>

@endsection
