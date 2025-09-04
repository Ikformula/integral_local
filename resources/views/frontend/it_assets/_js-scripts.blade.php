<datalist id="asset_meta_keys_datalist">
    @foreach($data['asset_meta_keys'] as $meta_key)
        <option>{{ $meta_key->meta_key }}</option>
    @endforeach
</datalist>

<script src="{{ asset('adminlte3.2/plugins/select2/js/select2.full.min.js') }}"></script>
<script>
    $('.select2').select2({
        theme: 'bootstrap4'
    });
</script>

<script>

    function removeThisMeta(rowID){
        $('#meta-' + rowID).remove();
    }

    var meta_rows_count = {{ $starting_count }};
    function AddNewMeta(rowID){
        meta_rows_count++;
        var new_meta_row = `<div class="row py-2" id="meta-${meta_rows_count}">
                            <div class="col-md-4 col-sm-4">
                                <div class="form-group">
                                        <input type="text" class="form-control" name="asset_meta_key[${meta_rows_count}]" list="asset_meta_keys_datalist">
                                    <label>Key</label>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="asset_meta_value[${meta_rows_count}]">
                                    <label>Value</label>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <button type="button" class="btn btn-danger" onclick="removeThisMeta(${meta_rows_count})"><i class="fa fa-times"></i></button>
                                <button type="button" class="btn btn-primary" onclick="AddNewMeta(${meta_rows_count})"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>`;

        $(new_meta_row).insertAfter('#meta-' + rowID);
    }

    const staff_members = {!! $data['staff_members'] !!};

    function selectElement(id, valueToSelect) {
        if(typeof valueToSelect != 'undefined' && valueToSelect !== null) {
            let element = document.getElementById(id);
            element.value = valueToSelect;
        }
    }

    $('#staff_ara_id').change(function(){
        let staff_ara_id = $(this).val();
        let selectedStaff = staff_members.find(staff => staff.staff_ara_id == staff_ara_id);

        selectElement('department_name', selectedStaff.department_name);
        selectElement('office_location', selectedStaff.location_in_hq);
    });
</script>
