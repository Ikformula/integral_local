@extends('frontend.layouts.app')

@section('title', 'View Asset' )

@push('after-styles')
    <style>
        .scrollable-div {
            height: 500px; /* Change this value to set the desired fixed height */
            overflow: auto; /* This will enable vertical scrolling if content exceeds the height */
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-5">
                <div class="card arik-card shadow">
                    <div class="card-header">
                        IT Asset
                    </div>
                    <div class="card-body p-0">
                        <table class="table" id="it-asset">
                            <tbody>
                            @foreach($it_asset->getAttributes() as $key => $value)
                                @if(!in_array($key, ['deleted_at', 'id', 'user_id']))
                                <tr>
                                    <td><span class="float-right font-weight-bold">{{ ucfirst(str_replace('_', ' ', $key)) }}</span></td>
                                    <td>{{ $value }}</td>
                                </tr>
                                @endif
                            @endforeach
                            @foreach($it_asset->assetMeta as $asset_meta)
                                <tr>
                                    <td><span class="float-right font-weight-bold">{{ ucfirst(str_replace('_', ' ', $asset_meta->meta_key)) }}</span></td>
                                    <td>{{ $asset_meta->meta_value }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @if($it_asset->assetHistories->count())
            <div class="col-md-7">
                <div class="card arik-card shadow">
                    <div class="card-header shadow">
                        Change History (From most recent)
                    </div>
                    <div class="card-body scrollable-div">
                        <div class="timeline">
                            @foreach($it_asset->assetHistories->reverse() as $history)
                                <!-- timeline time label -->
                                <div class="time-label">
                                    <span class="bg-navy">{{ $history->created_at->toDayDateTimeString() }}</span>
                                </div>
                                <!-- /.timeline-label -->
                                <!-- timeline item -->
                                <div>
                                    <i class="fas fa-pen-alt bg-blue"></i>
                                    <div class="timeline-item">
                                        <span class="time"><i class="fas fa-clock"></i> {{ $history->created_at->diffForHumans() }}</span>
                                        <h3 class="timeline-header"><a href="#">{{ $history->changedByUser->full_name }}</a> updated this Asset</h3>

                                        <div class="timeline-body">
                                            <div class="table-responsive"> <table class="table table-bordered">
                                                    @php($history_body[$history->id] = json_decode($history->body, true))
                                                    {!! generateRows($history_body[$history->id])  !!}
                                                </table></div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END timeline item -->
                            @endforeach
                            <div>
                                <i class="fas fa-clock bg-gray"></i>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            @endif
        </div>
    </div>
@endsection
