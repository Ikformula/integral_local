@if(!isset($presentation_mode) || $presentation_mode != 'on')
<div class="row my-2">
    <div class="col-12">
        <button type="button" class="btn btn-primary float-right" onclick="exportTableToXLSX('{{ $table_id }}')" id="excel_btn_{{ $table_id }}">Export to Excel</button>
        @if(isset($_GET['business_area_id']) && $_GET['business_area_id'] == 9)
        @php
            $query = request()->query();
            if(isset($query['yam'])) {
                unset($query['yam']);
                $buttonText = 'Hide Month/Year';
            } else {
                $query['yam'] = 1;
                $buttonText = 'Show Month/Year';
            }
            $url = url()->current() . (count($query) ? '?' . http_build_query($query) : '');
        @endphp
        <a href="{{ $url }}" class="btn bg-maroon mb-2">{{ $buttonText }}</a>
            @endif
    </div>
</div>
@endif
