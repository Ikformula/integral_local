<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\BusinessArea;
use Illuminate\Http\Request;
use App\Models\ScoreCardFormField;

class ScoreCardFormFieldsController extends Controller
{
    public function index()
    {
        $formFields = ScoreCardFormField::withoutClosureScope()->get();
        $business_areas = BusinessArea::withTrashed()->get();
        return view('frontend.business_goals.score_card_form_fields', compact('formFields', 'business_areas'));
    }

    public function store(Request $request)
    {
        $formField = ScoreCardFormField::create($request->all());
//        if($request->business_area_id == 2){
//            $arr = [];
//            $arr['label']  = $formField->label.' (Comment)';
//            $arr['form_type']  = 'text';
//            $comment_field =
//        }
        return response()->json(['success' => true,
            'formField' => $formField,
            'business_area_id' => $formField->business_area_id,
            'business_area' => $formField->businessArea->name,
            'colour' => 'success',
            'message' => 'Form field created successfully',
            'label' => $formField->label,
            'placeholder' => $formField->placeholder,
            'form_type' => $formField->form_type,
            'target_value' => $formField->target_value,
            'unit' => $formField->unit,
        ]);
//        return back()->withFlashSuccess('Created successfully');
    }

    public function update(Request $request, ScoreCardFormField $form_field)
    {
        $form_field->update($request->all());
//        return back()->withFlashSuccess('Updated successfully');
        return response()->json(['success' => true, 'colour' => 'success', 'message' => 'Form field updated successfully']);
    }

    public function destroy(ScoreCardFormField $form_field)
    {
        $form_field->delete();
//        return response()->json(['success' => true])
        return redirect()->route('frontend.business_goals.form_fields.index')->withFlashInfo('Deleted successfully');
    }
}
