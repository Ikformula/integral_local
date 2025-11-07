<!-- resources/views/frontend/avsec_vehicles/show.blade.php -->
@extends('frontend.layouts.app')

@section('title', 'Vehicles Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Vehicles Details</h3>
                </div>
                <div class="card-body">
                    <p><strong>Staff Ara Id:</strong> {{ $item->staff_ara_idRelation ? $item->staff_ara_idRelation->name_and_ara : '' }}</p>
                    <p><strong>Unit:</strong> {{ $item->staff_ara_idRelation ? $item->staff_ara_idRelation->department_name : '' }}</p>
                    <p><strong>Registered Name on the vehicle:</strong> {{ $item->registered_name_on_vehicle }}</p>
                    <p><strong>Type of Vehicle:</strong> {{ $item->vehicle_type }}</p>
                    <p><strong>Car Model:</strong> {{ $item->car_model }}</p>
                    <p><strong>Colour:</strong> {{ $item->colour }}</p>
                    <p><strong>Brand:</strong> {{ $item->brand }}</p>
                    <p><strong>Registration Number:</strong> {{ $item->reg_number }}</p>
                    <p><strong>Sticker Number:</strong> {{ $item->sticker_number ? $item->sticker_number : 'None given yet' }}</p>
                    <p><strong>Attended By:</strong> {{ $item->attended_by_user_idRelation ? $item->attended_by_user_idRelation->full_name : '' }}</p>
                    <p><strong>Registration Cert:</strong><br>
                        @if($item->registration_cert)
                            <img src="{{ asset('storage/' . $item->registration_cert) }}" alt="Registration Cert" class="img-thumbnail" style="max-width:200px; height:auto;">
                        @else
                            <em>No image available</em>
                        @endif
                    </p>
                    <p><strong>Proof Of Ownership:</strong><br>
                        @if($item->proof_of_ownership)
                            <img src="{{ asset('storage/' . $item->proof_of_ownership) }}" alt="Proof Of Ownership" class="img-thumbnail" style="max-width:200px; height:auto;">
                        @else
                            <em>No image available</em>
                        @endif
                    </p>

                    <a href="{{ route('frontend.avsec_vehicles.index') }}" class="btn btn-secondary">Back</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
