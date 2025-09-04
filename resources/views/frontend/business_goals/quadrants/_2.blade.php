{{--Ground Operations Weekly Report--}}
@if(!isset($presentation_mode))
@php
$ground_ops_stat_ids = [73, 81, 83, 93];
@endphp
<script>
    let groundOpsStats = '';
</script>
<div class="row" id="ground-ops-stats">
    @foreach($ground_ops_stat_ids as $ground_ops_stat_id)
        {{$ground_ops_stat_id}}

    @endforeach
</div>
@endif


@include('frontend.business_goals.partials.table2xlsx_button', ['table_id' => 'ba_'. $business_area_id] )
<table class="tableE table-hover table-striped table-sm mx-auto" id="ba_{{ $business_area_id }}">
    <thead>
    <tr>
        <th colspan="5" class="bg-navy">{{ $business_area->name }}  Report</th>
    </tr>
    <tr  style="background-color: #B3CEEB">
        <th></th>
        <th>Wk {{ $week_in_focus->week_number }}
{{--            <small>({{ $week_in_focus->from_day }} - {{ $week_in_focus->to_day }})</small>--}}
        </th>
        <th>Wk {{ $previousWeek->week_number }}
{{--            <small>({{ $presentation_data['titles']['last week'] }})</small>--}}
        </th>
        <th>Difference</th>
        <th>COMMENTS</th>
    </tr>
    </thead>
    <tbody>
    @php
        // dd($form_fields_collection->where('id', 79));
             $current_week_total = $prev_week_total = 0;
             // $custom_order = [73, 77, 79, 81, 83, 85, 89, 186, 91, 93, 95, 97, 99, 101, 103, 105, 107, 109, 188];
             if(\Illuminate\Support\Facades\Route::getCurrentRoute() == 'frontend.multi.business.areas'){
             $custom_order = [73, 77, 79, 81, 85, 272, 273, 391, 275, 389, 390, 89, 186, 404, 405, 91, 406, 93, 95, 97, 99, 101, 103, 105, 107, 109, 188];
             }else{
             $custom_order = [73, 77, 79, 83, 404, 405, 91, 406, 95, 97, 99, 101, 85, 272, 273, 391, 275, 389, 390, 89, 186, 93, 103, 105, 107, 109, 188, 81, 282, 283, 284, 285, 286, 287, 288, 289, 290, 291, 292, 293, 294, 295, 296, 297, 298, 299, 300, 301, 302, 303, 304, 305];
             }
             // Removed 274 on 17th of February, 2025 TODO: find a way to connect the views to the field opening windows db:score_card_form_field_closures
             // Removed 272 on 4th of March, 2025
             // $custom_order = [73, 75, 77, 78, 79, 80, 81, 82, 282, 283, 284, 285, 286, 287, 288, 289, 290, 291, 292, 293, 294, 295, 296, 297, 298, 299, 300, 301, 83, 84, 85, 86, 272, 273, 274, 275, 89, 90, 186, 187, 91, 92, 93, 94, 95, 96, 97, 98, 99, 100, 101, 102, 103, 104, 105, 106, 107, 108, 109, 188, 110];

    $complain_ids = [91, 95, 97, 99, 101, 404, 405, 406];
    $online_glitches = [272, 273, 274, 275, 391];
    @endphp

    {{--    @foreach($form_fields_collection->where('form_type', 'number') as $field)--}}
    @foreach($custom_order as $field_id)
        @php
            $field = $form_fields_collection->where('id', $field_id)->first();
               $current_week = isset($presentation_data['current_week'][$field->id]) && isset($presentation_data['current_week'][$field->id]['total']) ? $presentation_data['current_week'][$field->id]['total'] : (isset($presentation_data['current_week'][$field->id]['average']) ? $presentation_data['current_week'][$field->id]['average'] : null);
                $prev_week = isset($presentation_data['previous_week'][$field->id]) && isset($presentation_data['previous_week'][$field->id]['total']) ? $presentation_data['previous_week'][$field->id]['total'] : (isset($presentation_data['previous_week'][$field->id]['average']) ? $presentation_data['previous_week'][$field->id]['average'] : null);
                $variance = isset($current_week) && isset($prev_week) ? ($current_week - $prev_week) : 'NIL';
                $variance = $variance != 0 ? $variance : 'NIL';
                // $current_week_total += ($field->form_type == 'number' && isset($current_week)) ? $current_week : 0;
                // $prev_week_total += ($field->form_type == 'number' && isset($prev_week)) ? $prev_week : 0;
                $variance_direction = is_numeric($variance) ? ($variance < 0 ? 'decrease' : 'increase') : '';
                if(findFirstArrayWithValue($form_fields, $field->label.' (Comment)')){
                    $comment[$field->id] = findFirstArrayWithValue($form_fields, $field->label.' (Comment)')['id'];
                }else{
                    $comment[$field->id] = '';
                }

                $bg_colour = null;
                if($field_id == 83){
                   $bg_colour = '#f49cbb';
                }elseif($field_id == 390){
                   $bg_colour = '#F5B4DE';
                }elseif($field_id == 85){
                   $bg_colour = '#788bff';
                }elseif(in_array($field_id, $complain_ids)){
                    $bg_colour = '#f4e3e9';
                }elseif(in_array($field_id, $online_glitches)){
                    $bg_colour = '#d1d6f2';
                }

        @endphp

        <tr style=" @if(isset($bg_colour)) background-color: {{ $bg_colour }}; @endif @if($field_id == 81) font-weight: bold; @endif ">
            <td>{{ $field->label }}</td>
            <td>{{ isset($field->unit, $current_week) && $field->unit == '₦' ? '₦' : ''}}{{ is_numeric($current_week) ? checkIntNumber($current_week) : isset($current_week) ?? 'N/A'}}{{ isset($field->unit, $current_week) && $field->unit != '₦' ? $field->unit : ''}}</td>
            <td>{{ isset($field->unit, $prev_week) && $field->unit == '₦' ? '₦' : ''}}{{ is_numeric($prev_week) ? checkIntNumber($prev_week) : isset($prev_week) ?? 'N/A'}}{{ isset($field->unit, $prev_week) && $field->unit != '₦' ? $field->unit : ''}}</td>
            <td>{{ (isset($field->unit) && $field->unit == '₦' && is_numeric($variance)) ? '₦' : '' }}{{ is_numeric($variance) ? checkIntNumber(abs($variance)) : 'NIL'}}{{ (isset($field->unit) && $field->unit != '₦' && is_numeric($variance)) ? $field->unit : '' }} {{ $variance_direction }}</td>
            <td>{{ $presentation_data['current_week'][$comment[$field->id]]['total'] ?? 'N/A'}}</td>
        </tr>

@if(!isset($presentation_mode) && isset($ground_ops_stat_ids) && in_array($field_id, $ground_ops_stat_ids))
    @php
    $stat_current_week =  (isset($field->unit, $current_week) && $field->unit == '₦' ? '₦' : '').(is_numeric($current_week) ? checkIntNumber($current_week) : isset($current_week) ?? 'N/A').( isset($field->unit, $current_week) && $field->unit != '₦' ? $field->unit : '');
    $stat_prev_week =  (isset($field->unit, $prev_week) && $field->unit == '₦' ? '₦' : '').(is_numeric($prev_week) ? checkIntNumber($prev_week) : isset($prev_week) ?? 'N/A').( isset($field->unit, $prev_week) && $field->unit != '₦' ? $field->unit : '');
        $should_reduce = $field_id == 83 ? 1 : 0;
        $stat_variance = calculateVariance($current_week, $prev_week, $should_reduce);
        // $colour = isset($current_week, $prev_week) ? ($current_week >= $prev_week ? 'text-success' : 'text-danger') : '';
        $colour = '';
    @endphp
        <script>
            groundOpsStats = groundOpsStats + `<div class="col-lg-3">
                    <div class="card">
                        <div class="card-header border-0 pb-0" style="">
                            <h4 class="mb-0" style="display:inline;">{{ $field->label }}</h4>
                                    {!!  $stat_variance  !!}

            </div>
            <div class="card-body pt-0 pb-1">
                <div class="row">
                    <div class="col-6">
                        <p class="text-muted mb-0">Wk {{ $week_in_focus->week_number }}</p>
                                    <strong class="d-lg-none {{ $colour }}">{{ $stat_current_week }}</strong>
                                    <h4 class="text-bold d-none d-lg-block mb-0 {{ $colour }}">{{ $stat_current_week }}</h4>
                                </div>
                                <div class="col-6">
                                    <p class="text-muted mb-0">Wk {{ $previousWeek->week_number }}</p>
                                    <strong class="d-lg-none">{{ $stat_prev_week }}</strong>
                                    <h4 class="text-bold d-none d-lg-block mb-0">{{ $stat_prev_week }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`;
        </script>
        @endif
    @endforeach
    </tbody>
</table>

<script>
    document.getElementById('ground-ops-stats').innerHTML = groundOpsStats;
</script>

@if(!isset($no_daily))
<div class="row mt-3">
    <div class="col-12">
        @include('frontend.business_goals.dailies._2')
    </div>
</div>
    @endif
