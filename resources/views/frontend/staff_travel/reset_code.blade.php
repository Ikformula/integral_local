@extends('frontend.layouts.app')

@section('title', 'Staff Travel Password Reset' )

@section('content')

    <section class="content">
        <div class="container-fluid">
            <!-- Info boxes -->
            <div class="row justify-content-center">
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <span class="text-info">{{ $message }}</span>
                            <form action="{{ route('frontend.staff_travel.verify_reset_code') }}" method="POST">
                                @csrf

                                <div class="form-group">
                                    <label for="reset_token">Reset Token</label>
                                    <input type="text" class="form-control" name="reset_token" id="reset_token" required>
                                </div>

                                <div class="form-group">
                                    <label for="password">New password</label>
                                    <input type="password" class="form-control" name="password" id="password" required>
                                </div>

                                <div class="form-group">
                                    <label for="password_confirmation">Enter the new password again</label>
                                    <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" required>
                                    <small class="text-info">Must be same as the 'New password' field</small>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn bg-maroon">Reset</button>
                                </div>

                            </form>
                        </div>
                    </div>

                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

        </div><!--/. container-fluid -->
    </section>
@endsection
