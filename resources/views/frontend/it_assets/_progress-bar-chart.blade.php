@php $colours = [
    'navy',
    'maroon',
     'info',
    'danger',
     'warning',
     'success'
];
@endphp


        <div class="progress-group">
            {{ $key }}
            <span class="float-right"><b>{{ $value }}</b>/{{ $total }}</span>
            <div class="progress progress-sm">
                @if($total >= 1)
                    <div class="progress-bar bg-{{ isset($colour) ? $colour : $colours[array_rand($colours)] }}" style="width: {{ ($value/$total) * 100}}%"></div>
                @else
                    <div class="progress-bar bg-{{ isset($colour) ? $colour : $colours[array_rand($colours)] }}" style="width: 0"></div>
                @endif
            </div>
        </div>
