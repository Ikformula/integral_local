<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\StaffTravelBeneficiary;
use App\Models\StaffMember;
use App\Models\StbRegistrationWindow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffTravelBeneficiaryController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->can('manage staff travel portal')) {
            $items = StaffTravelBeneficiary::all();
        } elseif ($user->staff_member) {
            $staffMember = $user->staff_member;

            if ($staffMember->advisorTo && $staffMember->advisorTo->count()) {
                $items = $staffMember->advisedBeneficiaries();
            } else {
                $items = StaffTravelBeneficiary::where('staff_ara_id', $staffMember->staff_ara_id)->get();
            }

        } else {
            $items = collect();
        }

        return view('frontend.staff_travel_beneficiaries.index', compact('items'));
    }

    public function pendingBeneficiaries()
    {
        $user = Auth::user();

        if ($user->can('manage staff travel portal')) {
            $items = StaffTravelBeneficiary::whereNull('status')->get();
        } elseif ($user->staff_member) {
            $staffMember = $user->staff_member;

            if ($staffMember->advisorTo && $staffMember->advisorTo->count()) {
                $items = $staffMember->advisedBeneficiaries()->whereNull('status');
            } else {
                $items = StaffTravelBeneficiary::where('staff_ara_id', $staffMember->staff_ara_id)->get();
            }

        } else {
            $items = collect();
        }

        return view('frontend.staff_travel_beneficiaries.index', compact('items'));
    }

    public function indexMine()
    {
        $user = Auth::user();
        if ($user->staff_member) {
            $staffMember = $user->staff_member;
            $items = StaffTravelBeneficiary::where('staff_ara_id', $staffMember->staff_ara_id)->get();
        } else {
            $items = collect();
        }

        return view('frontend.staff_travel_beneficiaries.index', compact('items'))->with([
            'mode' => 'personal'
        ]);
    }


    private function inRegistrationWindow()
    {
        $now = now();
        return StbRegistrationWindow::whereNull('closed_at')
            ->where('from_date', '<=', $now)
            ->where('to_date', '>', $now)
            ->count();
    }

    public function create(Request $request)
    {
        if(!$this->inRegistrationWindow())
            return back()->withErrors('Not currently in a Beneficiary Registration Window');
        $user = Auth::user();
        $staff_member = $user->staff_member;

        $max_beneficiaries = getSettingValue('max_number_of_beneficiaries');
        if ($user->can('manage staff travel portal')) {
            $staffMembers = StaffMember::all();
        } elseif ($staff_member) {
            if ($staff_member->advisorTo && $staff_member->advisorTo->count() && !$request->has('personal')) {
                $staffMembers = $staff_member->advisees;
            }else{
                if ($max_beneficiaries <= $staff_member->staffTravelBeneficiaries->count())
                    return back()->withErrors('Maximum number of beneficiaries attained');
            $staffMembers = collect([$staff_member]);
            }

        } else {
            $staffMembers = collect();
        }

        return view('frontend.staff_travel_beneficiaries.create', compact('staffMembers'));
    }

    public function store(Request $request)
    {
        if(!$this->inRegistrationWindow())
            return back()->withErrors('Not currently in a Beneficiary Registration Window');

        $staff_member = StaffMember::where('staff_ara_id', $request->staff_ara_id)->first();
        if(!$staff_member)
            return back()->withErrors('Invalid Staff ARA ID');

        $user = Auth::user();
        $user_staff_member = $user->staff_member;
        if (!$user->can('manage staff travel portal')) {
//            dd (!isset($user_staff_member->advisees) || !in_array($request->staff_ara_id, $user_staff_member->advisees->pluck('staff_ara_id')->toArray()));
            if (!$user_staff_member && $request->staff_ara_id != $user_staff_member->staff_ara_id && (isset($user_staff_member->advisees) && !in_array($request->staff_ara_id, $user_staff_member->advisees->pluck('staff_ara_id')->toArray()))) {
                return redirect()->back()->withErrors('You are not authorized to create this record.');
            }
        }


        $max_beneficiaries = getSettingValue('max_number_of_beneficiaries');
        if ($max_beneficiaries <= $staff_member->staffTravelBeneficiaries->count())
            return back()->withErrors('Maximum number of beneficiaries attained');

        try {
            $data = $request->all();

            // Handle photo upload
            if ($request->hasFile('photo')) {
                $data['photo'] = $request->file('photo')->store('staff_travel_photos', 'public');
            }

            StaffTravelBeneficiary::create($data);

            if($request->has('personal'))
                return redirect()->route('frontend.staff_travel_beneficiaries.index.mine')
                ->withFlashSuccess('StaffTravelBeneficiary created successfully!');

            return redirect()->route('frontend.staff_travel_beneficiaries.index')
                ->withFlashSuccess('StaffTravelBeneficiary created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Error creating StaffTravelBeneficiary: ' . $e->getMessage());
        }
    }

    public function show(Request $request, StaffTravelBeneficiary $item)
    {
        return view('frontend.staff_travel_beneficiaries.show', compact('item'))->with([
            'mode' => $request->has('personal') ? 'personal' : ''
        ]);
    }

    public function edit(Request $request, StaffTravelBeneficiary $item)
    {
        if(!$this->inRegistrationWindow())
            return back()->withErrors('Not currently in a Beneficiary Registration Window');

        if (!$this->canManageItem($item)) {
            abort(403, 'Unauthorized');
        }

        $user = Auth::user();
        if ($user->can('manage staff travel portal')) {
            $staffMembers = StaffMember::all();
        } elseif ($user->staff_member) {
            $staff_member = $user->staff_member;
            if ($staff_member->advisorTo && $staff_member->advisorTo->count() && !$request->has('personal')) {
                $staffMembers = $staff_member->advisees;
            }else{
                $max_beneficiaries = getSettingValue('max_number_of_beneficiaries');
                if ($max_beneficiaries <= $staff_member->staffTravelBeneficiaries->count())
                    return back()->withErrors('Maximum number of beneficiaries attained');
                $staffMembers = collect([$staff_member]);
            }
        } else {
            $staffMembers = collect();
        }

        return view('frontend.staff_travel_beneficiaries.edit', compact('item', 'staffMembers'));
    }

    public function update(Request $request, StaffTravelBeneficiary $item)
    {
        if(!$this->inRegistrationWindow())
            return back()->withErrors('Not currently in a Beneficiary Registration Window');

        if (!$this->canManageItem($item)) {
            return redirect()->back()->withErrors('You are not authorized to update this record.');
        }

        try {
            $data = $request->all();

            // Handle photo upload
            if ($request->hasFile('photo')) {
                // Delete the old photo if it exists
                if ($item->photo && \Storage::disk('public')->exists($item->photo)) {
                    \Storage::disk('public')->delete($item->photo);
                }

                // Store the new photo
                $data['photo'] = $request->file('photo')->store('staff_travel_photos', 'public');
            }

            $item->update($data);

            if($request->has('personal'))
                return redirect()->route('frontend.staff_travel_beneficiaries.index.mine')
                    ->withFlashSuccess('StaffTravelBeneficiary created successfully!');


            return redirect()->route('frontend.staff_travel_beneficiaries.index')
                ->withFlashSuccess('StaffTravelBeneficiary updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Error updating StaffTravelBeneficiary: ' . $e->getMessage());
        }
    }


    public function approve(StaffTravelBeneficiary $item, Request $request)
{
    if (!$this->canManageItem($item)) {
        return redirect()->back()->withErrors('You are not authorized to approve this record.');
    }

    try {
        $item->update([
            'status' => 'approved',
            'actioned_by' => Auth::id(),
            'actioned_time' => now(),
            'actioned_comment' => $request->input('actioned_comment', 'Approved'),
        ]);

        return redirect()->back()
            ->withFlashSuccess('Beneficiary approved successfully!');
    } catch (\Exception $e) {
        return redirect()->back()->withErrors('Error approving beneficiary: ' . $e->getMessage());
    }
}

    public function disapprove(StaffTravelBeneficiary $item, Request $request)
    {
        if (!$this->canManageItem($item)) {
            return redirect()->back()->withErrors('You are not authorized to disapprove this record.');
        }

        try {
            $item->update([
                'status' => 'disapproved',
                'actioned_by' => Auth::id(),
                'actioned_time' => now(),
                'actioned_comment' => $request->input('actioned_comment', 'Disapproved'),
            ]);

            return redirect()->back()
                ->withFlashSuccess('Beneficiary disapproved successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Error disapproving beneficiary: ' . $e->getMessage());
        }
    }

    public function destroy(StaffTravelBeneficiary $item)
    {
        if (!$this->canManageItem($item)) {
            return redirect()->back()->withErrors('You are not authorized to delete this record.');
        }

        try {
            // Delete the photo if it exists
            if ($item->photo && \Storage::disk('public')->exists($item->photo)) {
                \Storage::disk('public')->delete($item->photo);
            }

            // Delete the record
            $item->delete();

            return redirect()->back()
                ->withFlashSuccess('StaffTravelBeneficiary deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Error deleting StaffTravelBeneficiary: ' . $e->getMessage());
        }
    }

    /**
     * Determine if the currently authenticated user can manage the given record.
     */
    protected function canManageItem(StaffTravelBeneficiary $item): bool
    {
        $user = Auth::user();

        if ($user->can('manage staff travel portal')) {
            return true;
        }

        if ($user->staff_member) {
            // Direct ownership
            if ($user->staff_member->staff_ara_id === $item->staff_ara_id) {
                return true;
            }

            // If advisor to this beneficiary's staff member
            $departments = $user->staff_member->advisorTo()->pluck('department_name');
            $beneficiaryOwner = StaffMember::where('staff_ara_id', $item->staff_ara_id)->first();

            if ($beneficiaryOwner && $departments->contains($beneficiaryOwner->department_name)) {
                return true;
            }
        }

        return false;
    }

}
