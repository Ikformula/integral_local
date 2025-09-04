{{--Ground Operations Weekly Report--}}
@php
    // dd($form_fields_collection->where('id', 79));
         $current_week_total = $prev_week_total = 0;
         // $custom_order = [73, 77, 79, 81, 83, 85, 89, 186, 91, 93, 95, 97, 99, 101, 103, 105, 107, 109, 188];
         if(\Illuminate\Support\Facades\Route::getCurrentRoute() == 'frontend.multi.business.areas'){
         $custom_order = [73, 77, 79, 81, 85, 273, 391, 275, 389, 390, 89, 186, 91, 93, 95, 97, 99, 101, 103, 105, 107, 109, 188];
             }else{
             $custom_order = [73, 77, 79, 83, 91, 95, 97, 99, 101, 85, 273, 391, 275, 389, 390, 89, 186, 93, 103, 105, 107, 109, 188, 81, 282, 283, 284, 285, 286, 287, 288, 289, 290, 291, 292, 293, 294, 295, 296, 297, 298, 299, 300, 301, 302, 303, 304, 305];
             }
@endphp

@include('frontend.business_goals.dailies._table')
