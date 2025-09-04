@extends('frontend.layouts.app')

@section('title', $workflow->title)

@section('content')
    <section class="content">
        <div class="container-fluid">

        <div class="row justify-content-center align-items-center mb-3">
        <div class="col col-sm-10 align-self-center">
            <div class="card">
                <div class="card-header">
                    <strong>
                       {{ $workflow->title }} - {{ $workflow->type }}
                    </strong>
                </div>

                <div class="card-body">
                    <object data="{{ asset('workflow_files/'.$workflow->file_path) }}" type="application/pdf" width="100%" height="700"> <a href="{{ asset('workflow_files/'.$workflow->file_path) }}">{{ $workflow->file_path }}</a></object>
                </div>
                <div class="card-footer">
                    @if(is_null($workflow->approved_at) && is_null($workflow->rejected_at))
                        @if(isset($logged_in_user->staff_member) && $logged_in_user->staff_member->staff_ara_id == $workflow->approver_staff_ara_id)
                            <form action="" method="POST" class="form-inline">
                                @csrf
                                <input type="hidden" name="response_type" value="approve">
                                <button type="submit" class="btn btn-success">Approve</button>
                            </form>

                            <form action="" method="POST" class="form-inline">
                                @csrf
                                <input type="hidden" name="response_type" value="reject">
                                <button type="submit" class="btn btn-success">Reject</button>
                            </form>
                            @endif
                        @else
                            {{ isset($workflow->approved_at) ? 'Approved at '.$workflow->approved_at->toDateDayTimeString() : '' }}
                            {{ isset($workflow->rejected_at) ? 'Rejected at'.$workflow->rejected_at->toDateDayTimeString() : '' }}
                    @endif
                </div>
            </div>
        </div>
    </div>
        </div>
    </section>
@endsection
