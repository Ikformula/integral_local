<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\AvsecVehicle;
use App\Models\AvsecVehicleStickerCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Traits\OutgoingMessagesTrait;

class AvsecVehicleController extends Controller
{
    use OutgoingMessagesTrait;
    public function index()
    {
        $logged_in_user = auth()->user();
        if ($logged_in_user->can('manage avsec portals')) {
            $items = AvsecVehicle::all();
        } elseif ($logged_in_user->can('update other staff info')) {
            $items = AvsecVehicle::where('uploaded_by_user_id', $logged_in_user->id)->get();
        } elseif ($logged_in_user->staff_member) {
            $items = AvsecVehicle::where('staff_ara_id', $logged_in_user->staff_member->staff_ara_id)->get();
        } else {
            return redirect()->home()->withErrors('Unauthorized');
        }
        return view('frontend.avsec_vehicles.index', compact('items'));
    }

    public function create()
    {
        $logged_in_user = auth()->user();

        // If the user is a staff member (not an AVSEC manager), prevent creating more than one vehicle
        if ($logged_in_user->staff_member && !$logged_in_user->can('manage avsec portals')) {
            $staffAra = $logged_in_user->staff_member->staff_ara_id;
            $exists = AvsecVehicle::where('staff_ara_id', $staffAra)->exists();
            if ($exists) {
                return redirect()->back()->withErrors('You already have a registered vehicle. Please edit the existing record or contact AVSEC.');
            }
        }
        return view('frontend.avsec_vehicles.create');
    }

    public function store(Request $request)
    {
        try {
            $data = $request->all();
            $logged_in_user = auth()->user();

            // If the user is a staff member (not an AVSEC manager), prevent creating more than one vehicle
            if ($logged_in_user->staff_member && !$logged_in_user->can('manage avsec portals')) {
                $staffAra = $logged_in_user->staff_member->staff_ara_id;
                $exists = AvsecVehicle::where('staff_ara_id', $staffAra)->exists();
                if ($exists) {
                    return redirect()->back()->withErrors('You already have a registered vehicle. Please edit the existing record or contact AVSEC.');
                }
                // Force staff_ara_id to authenticated user's staff_ara_id to avoid spoofing
                $data['staff_ara_id'] = $staffAra;
            }

            // Always record who uploaded the record
            $data['uploaded_by_user_id'] = $logged_in_user->id;

            // Handle file and image uploads
            foreach ($request->allFiles() as $field => $file) {
                if ($file->isValid()) {
                    // Decide folder based on field name
                    $folder = (strpos($field, 'image') !== false) ? 'images' : 'files';

                    // Store file in storage/app/public/{folder}
                    $path = $file->store($folder, 'public');
                    $data[$field] = $path;
                }
            }

            AvsecVehicle::create($data);

            return redirect()->route('frontend.avsec_vehicles.index')
                ->withFlashSuccess('Vehicles created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors('Error creating Vehicles: ' . $e->getMessage());
        }
    }

    public function show(AvsecVehicle $item)
    {
        return view('frontend.avsec_vehicles.show', compact('item'));
    }

    public function edit(AvsecVehicle $item)
    {
        $user = auth()->user();
        if ($item->approved_at && !$user->can('manage avsec portals')) {
            return redirect()->route('frontend.avsec_vehicles.index')->withErrors('Only AVSEC managers can edit approved vehicles.');
        }

        return view('frontend.avsec_vehicles.edit', compact('item'));
    }

    public function update(Request $request, AvsecVehicle $item)
    {
        try {
            $data = $request->all();

            // If previously disapproved, reset disapproved_at/disapproval_reason on update
            if ($item->disapproved_at) {
                $item->disapproved_at = null;
                $item->disapproval_reason = null;
            }

            // Handle file and image uploads
            foreach ($request->allFiles() as $field => $file) {
                if ($file->isValid()) {
                    $folder = (strpos($field, 'image') !== false) ? 'images' : 'files';
                    $path = $file->store($folder, 'public');
                    $data[$field] = $path;
                }
            }

            if ($request->has('sticker_category_id')) {
                $category = AvsecVehicleStickerCategory::find($request->sticker_category_id);
                if ($category) {
                    $item->sticker_category_id = $request->sticker_category_id;
                    $item->effective_date = $request->effective_date;
                    $item->expiration_date = Carbon::parse($request->effective_date)->addMonths($category->validity_in_months);
                    $item->approved_at = now();
                    $item->disapproved_at = null;
                    $item->disapproval_reason = null;

                    unset($data);
                    $data['subject'] = "AVSEC Vehicle Registration";
                    $data['greeting'] = "Dear " . $item->staff_ara_idRelation->full_name . ',';
                    $data['line'][] = "Kindly note that a sticker has been assigned to you vehicle registered with Arik AVSEC.";
                    $data['line'][] = "Your sticker number is: " . $item->sticker_number;

                    $data['action_url'] = route('frontend.avsec_vehicles.index');
                    $data['action_text'] = "AVSEC Vehicle Registrations";
                    $data['to'] = $item->staff_ara_idRelation->email;
                    $data['cc'] = ['mariam.omoniyi@arikair.com'];
                    $data['to_name'] = $item->staff_ara_idRelation->full_name;

                    $this->storeMessage($data, null);
                }
            }

            $item->update($data);
            $item->save();

            return redirect()->route('frontend.avsec_vehicles.index')
                ->withFlashSuccess('Vehicles updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors('Error updating Vehicles: ' . $e->getMessage());
        }
    }

    public function approve(Request $request, AvsecVehicle $item)
    {
        $user = auth()->user();
        if (!$user->can('manage avsec portals')) {
            return redirect()->back()->withErrors('Unauthorized');
        }
        if ($item->approved_at) {
            return redirect()->back()->withFlashWarning('Vehicle already approved.');
        }
        $item->approved_at = now();
        $item->disapproved_at = null;
        $item->disapproval_reason = null;
        $item->save();
        return redirect()->back()->withFlashSuccess('Vehicle approved successfully.');
    }

    public function disapprove(Request $request, AvsecVehicle $item)
    {
        $user = auth()->user();
        if (!$user->can('manage avsec portals')) {
            return redirect()->back()->withErrors('Unauthorized');
        }
        if ($item->approved_at) {
            return redirect()->back()->withFlashWarning('Vehicle already approved. Cannot disapprove.');
        }

        $item->disapproved_at = now();
        $item->disapproval_reason = $request->input('disapproval_reason');
        $item->save();
        return redirect()->back()->withFlashSuccess('Vehicle disapproved successfully.');
    }


    /**
     * Allow AVSEC managers to reopen an approved vehicle so staff can update it.
     */
    public function reopen(Request $request, AvsecVehicle $item)
    {
        $user = auth()->user();
        if (!$user->can('manage avsec portals')) {
            return redirect()->back()->withErrors('Unauthorized');
        }

        if (is_null($item->approved_at)) {
            return redirect()->back()->withFlashWarning('Vehicle is not approved.');
        }

        // unset approval so the staff member can edit
        $item->approved_at = null;
        $item->save();

        return redirect()->back()->withFlashSuccess('Vehicle has been reopened for editing.');
    }



    public function destroy(AvsecVehicle $item)
    {
        try {
            $item->delete();
            return redirect()->route('frontend.avsec_vehicles.index')
                ->withFlashSuccess('Vehicles deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors('Error deleting Vehicles: ' . $e->getMessage());
        }
    }
}
