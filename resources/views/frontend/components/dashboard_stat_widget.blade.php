@php $colours = [
    'navy',
    'maroon',
    // 'info',
    'danger',
    // 'warning',
    // 'success'
];
@endphp
<div class="info-box shadow" style="border-radius: 1rem;">
    <span class="info-box-icon bg-{{ isset($colour) ? $colour : $colours[array_rand($colours)] }}" style="border-radius: 10px;"><i class="fas fa-{{ $icon ?? 'poll' }}"></i></span>

    <div class="info-box-content">
        <span class="info-box-text"><strong>{{ $title }}</strong></span>
        <span class="info-box-number">{{ $slot }}</span>
    </div>
    <!-- /.info-box-content -->
</div>
<!-- /.info-box -->

{{--<div class="small-box bg-{{ $colours[array_rand($colours)] }}">--}}
{{--    <div class="inner">--}}
{{--        <h3>{{ $slot }}</h3>--}}

{{--        <p>{{ $title }}</p>--}}
{{--    </div>--}}
{{--    <div class="icon">--}}
{{--        <i class="fas fa-{{ $icon ?? 'poll' }}"></i>--}}
{{--    </div>--}}
{{--</div>--}}

