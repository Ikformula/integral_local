@if($data_record)
    @php
        $data_value = $data_record->data_value ?? 'N/A';

    if($data_value !== 'N/A'){
        if($stat[2] == ''){
            $data_value = number_format($data_value);
        }else{
            $data_value = number_format($data_value, $stat[2]);
        }
    }
    $formatted_value = $data_value;
    @endphp

{{--    <div class="col-md-{{ $loop->iteration > 3 ? '3' : '4' }}">--}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-header border-0">
                <h4 class="mb-0" style="display: inline;">{{ $stat[1] }}</h4>
                @if(isset($data_record->target_variance_ui))
                    @php

                        $target_value = $data_record->target_value ? $data_record->target_value : 'N/A';
$store_target_value = $target_value;
$variance_disp = varianceDisplay($formatted_value, $target_value);
            if($target_value !== 'N/A'){
                if($stat[2] == ''){
                    $target_value = checkIntNumber($target_value);
                }else{
                    $target_value = checkIntNumber(number_format($target_value, $stat[2]));
                }
            }
                    @endphp
<button class="ml-5 btn bg-navy" style="display: inline;">Target: {{ $stat[3] == '₦' ? $stat[3] : '' }}{{ $target_value }}{{ $stat[3] == '%' ? $stat[3] : '' }}</button>

                    {!! $data_record->target_variance_ui !!}
                    @endif
            </div>
            <div class="card-body pt-0 pb-1">
                <h5 class="text-bold text-{{ !isset($variance_disp) || $variance_disp['variance_direction'] == '' ? 'dark' : ($variance_disp['variance_direction'] == 'decrease' ? 'danger' : 'success') }}">{{ $stat[3] == '₦' ? $stat[3] : '' }} {{ $formatted_value }}{{ $stat[3] == '%' ? $stat[3] : '' }}</h5>
            </div>
        </div>


    </div>
@else
    <div class="col-md-{{ $loop->iteration > 3 ? '3' : '4' }}">
        <h5>No Data</h5>
    </div>
@endif
