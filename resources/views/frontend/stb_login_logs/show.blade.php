<!-- resources/views/frontend/stb_login_logs/show.blade.php -->
@extends('frontend.layouts.app')

@section('title', 'StbLoginLog Details')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">StbLoginLog Details</h3>
                </div>
                <div class="card-body">
                    <p><strong>Staff ARA ID:</strong> {{ $item->staff_ara_id }}</p>
<p><strong>IP Address:</strong> {{ $item->ip_address }}</p>
<p><strong>Logged In At:</strong> {{ $item->logged_in_at }}</p>

                    <a href="{{ route('frontend.stb_login_logs.index') }}" class="btn btn-secondary">Back</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
