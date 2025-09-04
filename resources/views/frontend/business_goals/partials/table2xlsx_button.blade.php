@if(!isset($presentation_mode) || $presentation_mode != 'on')
<div class="row my-2">
    <div class="col-12">
        <button type="button" class="btn btn-primary float-right" onclick="exportTableToXLSX('{{ $table_id }}')" id="excel_btn_{{ $table_id }}">Export to Excel</button>
    </div>
</div>
@endif
