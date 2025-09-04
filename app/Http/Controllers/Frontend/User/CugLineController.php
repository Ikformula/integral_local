<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\CugLine;
use App\Models\StaffMember;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CugLineController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $stats  = null;
        if ($user->can('manage own unit info') || $user->can('update other staff info')) {
            $staffMembers = StaffMember::all();
            $cugLines = CugLine::with('staffMember')->get();
            $staff_with_cug = $cugLines->unique('staff_ara_id')->whereNotNull('staff_ara_id')->whereNotNull('phone_number')->count();
            $staff_with_confirmed_info = $cugLines->unique('staff_ara_id')->whereNotNull('confirmed_by');
            $staff_with_confirmed_info_no_cug = $staff_with_confirmed_info->whereNull('phone_number')->count();

            $stats = [
                [
                    'icon'  => 'fas fa-phone',
                    'title' => 'Total CUG Lines',
                    'value' => $cugLines->whereNotNull('phone_number')->count(),
                ],
                [
                    'icon'  => 'fas fa-users',
                    'title' => 'Staff With CUG',
                    'value' => $staff_with_cug,
                ],
                [
                    'icon'  => 'fas fa-users',
                    'title' => 'Staff Without CUG',
                    'value' => $staffMembers->count() - $staff_with_cug,
                ],
                [
                    'icon'  => 'fas fa-phone',
                    'title' => 'Group CUG Lines',
                    'value' => $cugLines->whereNull('staff_ara_id')->count(),
                ],
                [
                    'icon'  => 'fas fa-users',
                    'title' => 'Total Confirmed',
                    'value' => $staff_with_confirmed_info->count(),
                ],
                [
                    'icon'  => 'fas fa-users',
                    'title' => 'Total Confirmed with CUG',
                    'value' => $staff_with_confirmed_info->count() - $staff_with_confirmed_info_no_cug,
                ],
                [
                    'icon'  => 'fas fa-users',
                    'title' => 'Total Confirmed without CUG',
                    'value' => $staff_with_confirmed_info_no_cug,
                ],
            ];

        }else if($user->staff_member){
            $staff_ara_id = $user->staff_member->staff_ara_id;
            $staffMembers = StaffMember::where('staff_ara_id', $staff_ara_id)->get();
            if($staff_ara_id) {
                $cugLines = CugLine::where('staff_ara_id', $staff_ara_id)->with('staffMember')->get();
            }else{
                $cugLines = null;
            }
        }else{
            return redirect()->route('frontend.index');
        }


        return view('frontend.cug_lines.index', compact('cugLines', 'staffMembers', 'stats'));
    }

    public function store(Request $request)
    {
        $confirmed_by_user = auth()->user();
        if(!$confirmed_by_user->isAdmin() || !$confirmed_by_user->can('update other staff info'))
            redirect()->back()->withErrors('Unauthorized');

        $validatedData = $request->validate([
            'staff_ara_id' => 'nullable|exists:staff_member_details,staff_ara_id',
            'phone_number' => 'required|string|max:25',
//            'phone_type' => 'required|in:feature phone,smartphone',
//            'phone_model' => 'nullable|string|max:255',
            'surname' => 'nullable|string|max:255',
            'other_names' => 'nullable|string|max:255',
        ]);

        if($request->owner_category == 'individual'){
            $staffMember = StaffMember::where('staff_ara_id', $validatedData['staff_ara_id'])->first();
            if($staffMember){
                $validatedData['surname'] = $staffMember->surname;
                $validatedData['other_names'] = $staffMember->other_names;
            }
        }

        CugLine::create(array_merge($validatedData, ['confirmed_by' => $confirmed_by_user->id, 'confirmed_at' => now()]));

        return redirect()->route('frontend.cug_lines.index')->withFlashSuccess('CUG line added successfully.');
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:50',
            'surname' => 'required|string|max:50',
            'other_names' => 'nullable|string|max:50',
            'user_supplied_phone_number' => 'required|string|max:25',
//            'phone_type' => 'required|in:feature phone,smartphone',
//            'phone_model' => 'nullable|string|max:255',
            'dob' => 'nullable|string',
//            'nin' => 'nullable|string|min:11|max:11',
//            'serial_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:255',
        ]);

        $cugLine = CugLine::findOrFail($id);
        $cugLine->update(array_merge($validatedData, ['confirmed_by' => auth()->id(), 'confirmed_at' => now()]));

//        $prefill = "&entry.2005620554={$cugLine->first_name}+{$cugLine->surname}";
//        if(isset($cugLine->dob)){
//            $prefill .= "&entry.1496842130={$cugLine->dob}";
//        }
//        return redirect()->route('frontend.cug_lines.index')->withFlashSuccess('CUG line updated successfully.');
//        return redirect()->to('https://docs.google.com/forms/d/e/1FAIpQLSe-Mt-aYnClm__UeNyQY5xqo8z4KwIVvbaEloq1Nc8QQ0hd0w/viewform?usp=pp_url'.$prefill);
        return redirect()->to('https://forms.office.com/r/51VVnWVcX6');
    }

    public function confirm($id)
    {
        $cugLine = CugLine::findOrFail($id);
        $cugLine->update(['confirmed_by' => auth()->id(), 'confirmed_at' => now()]);

        return redirect()->route('frontend.cug_lines.index')->withFlashSuccess('CUG line updated successfully.');
    }

    public function destroy($id)
    {
        $cugLine = CugLine::findOrFail($id);
        $cugLine->delete();

        return redirect()->route('frontend.cug_lines.index')->withFlashSuccess('CUG line deleted successfully.');
    }

}
