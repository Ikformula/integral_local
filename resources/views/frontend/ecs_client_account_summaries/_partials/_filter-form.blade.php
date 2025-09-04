<div class="row">
    <div class="col">
        <form method="get">
            <div class="row ">
                <div class="col-md-3">
                    <div class="form-group">
                        <input type="date" min="{{ substr($earliest_date, 0, 10) }}" name="from_date"
                               value="{{ substr($from_date, 0, 10) }}" class="form-control">
                        <label>From Date (Earliest: {{ substr($earliest_date, 0, 10) }})</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <input type="date" name="to_date" max="{{ now()->toDateString() }}"
                               value="{{ substr($to_date, 0, 10) }}" class="form-control">
                        <label>To Date</label>
                    </div>
                </div>


                @if($logged_in_user->can('manage ecs processes'))
                <div class="col-md-3">
                        <div class="form-group">
                            <select class="form-control select2" name="client_id">
                                <option>-- Select One</option>
                                <option>All</option>
                                @if(isset($params['client_id']))
                                    @foreach(\App\Models\EcsClient::get() as $client)
                                        <option value="{{ $client->id }}" {{ $client->id == $params['client_id'] ? 'selected' : '' }}>{{ $client->name }}</option>
                                    @endforeach
                                @else
                                    @foreach(\App\Models\EcsClient::get() as $client)
                                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <label>Client</label>
                        </div>
                </div>
                @endif

                <div class="col-md-1">
                    <button type="submit" class="btn bg-maroon btn-block">Filter</button>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="radio" class="custom-control-input" id="allStatusesSwitch" name="filterSwitch" value="allStatusesSwitch" {{ (isset($params['filterSwitch']) ? ($params['filterSwitch'] == 'allStatusesSwitch' ? 'checked' : '') : (!count($params) ? 'checked' : '')) }}>
                            <label class="custom-control-label" for="allStatusesSwitch">All Statuses</label>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="radio" class="custom-control-input" id="disputeSwitch" name="filterSwitch" value="disputeSwitch" {{ (isset($params['filterSwitch']) && $params['filterSwitch'] == 'disputeSwitch') ? 'checked' : '' }}>
                            <label class="custom-control-label" for="disputeSwitch">Only Disputed</label>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="radio" class="custom-control-input" id="approvedSwitch" name="filterSwitch" value="approvedSwitch" {{ (isset($params['filterSwitch']) && $params['filterSwitch'] == 'approvedSwitch') ? 'checked' : '' }}>
                            <label class="custom-control-label" for="approvedSwitch">Only Approved</label>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="radio" class="custom-control-input" id="unattendedSwitch" name="filterSwitch" value="unattendedSwitch" {{ (isset($params['filterSwitch']) && $params['filterSwitch'] == 'unattendedSwitch') ? 'checked' : '' }}>
                            <label class="custom-control-label" for="unattendedSwitch">Only Non-attended</label>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
