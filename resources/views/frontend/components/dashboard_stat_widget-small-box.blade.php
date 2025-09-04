@php $colours = [
    // 'navy',
    // 'maroon',
    'primary',
    // 'danger',
    'warning',
    'secondary'
];
@endphp

<!-- small card -->
<div class="small-box shadow bg-{{ isset($colour) ? $colour : $colours[array_rand($colours)] }}" style="border-radius: 1rem;">
    <div class="inner">
        <h3>{{ $slot }}</h3>

        <p>{{ $title }}</p>
    </div>
    <div class="icon">
        <i class="fas fa-{{ $icon ?? 'poll' }}"></i>
    </div>
</div>
