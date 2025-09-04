<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\ItAsset;
use App\Models\AssetMeta;
use App\Models\StaffMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class AssetRegisterController extends Controller
{
    public function dashboard()
    {
        $all_itassets = ItAsset::all();
        // Stat boxes
        $totalAssets = $all_itassets->count();

        $sophosCount = $all_itassets->where('sophos_endpoint', 'yes')
            ->count();

        $withAssetTagCount = $all_itassets->whereNotNull('asset_tag')
            ->count();
        $withoutAssetTagCount = $all_itassets->whereNull('asset_tag')
            ->count();

        $data = $this->getData();
        foreach ($data['department_names'] as $dpt) {
            $department_names[] = $dpt->department_name;
        }

        unset($data['department_names']);
        $data['department_names'] = $department_names;

        // Tables
        foreach ($data['department_names'] as $department_name) {
            $statsByDepartmentAndDeviceType[$department_name]['total'] = $all_itassets->where('department_name', $department_name)
                ->count();
            foreach ($data['brands'] as $brand) {
                $statsByDepartmentAndDeviceType[$department_name][$brand] = $all_itassets->where('brand', $brand)
                    ->where('department_name', $department_name)
                    ->count();
            }
            foreach ($data['device_type'] as $device_type) {
                $statsByDepartmentAndDeviceType[$department_name][$device_type] = $all_itassets->where('device_type', $device_type)
                    ->where('department_name', $department_name)
                    ->count();
            }
        }

        foreach ($data['office_location'] as $office_location) {
            $statsByOfficeLocationAndDeviceType[$office_location]['total'] = $all_itassets->where('office_location', $office_location)
                ->count();
            foreach ($data['brands'] as $brand) {
                $statsByOfficeLocationAndDeviceType[$office_location][$brand] = $all_itassets->where('brand', $brand)
                    ->where('office_location', $office_location)
                    ->count();
            }

            foreach ($data['device_type'] as $device_type) {
                $statsByOfficeLocationAndDeviceType[$office_location][$device_type] = $all_itassets->where('device_type', $device_type)
                    ->where('office_location', $office_location)
                    ->count();
            }
        }


        // Pie charts
        $processorTypes = AssetMeta::where('meta_key', 'LIKE', '%processor%')->pluck('meta_value')->toArray();
        $ramSizes = AssetMeta::where('meta_key', 'LIKE', '%ram%')->pluck('meta_value')->toArray();
        $statusCounts = ItAsset::select('status')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();


        $pieColour['in service'] = '#001F3F';
        $pieColour['serviceable'] = '#FEAE00';
        $pieColour['nonserviceable'] = '#33000D';

        // Bar charts
        $brandCounts = ItAsset::select('brand')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('brand')
            ->pluck('count', 'brand')
            ->toArray();
        $deviceTypeCounts = ItAsset::select('device_type')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('device_type')
            ->pluck('count', 'device_type')
            ->toArray();


        return view('frontend.it_assets.dashboard', compact(
            'totalAssets',
            'withAssetTagCount',
            'withoutAssetTagCount',
            'statsByDepartmentAndDeviceType',
            'statsByOfficeLocationAndDeviceType',
            'processorTypes',
            'ramSizes',
            'statusCounts',
            'brandCounts',
            'sophosCount',
            'deviceTypeCounts',
            'data',
            'pieColour'
        ));
    }

    public function index()
    {
        $it_assets = ItAsset::paginate(400);
        return view('frontend.it_assets.index')->with([
            'it_assets' => $it_assets
        ]);
    }

    public function assetsByStaff()
    {
        $it_assets = ItAsset::all();
        $device_types = ItAsset::select('device_type')->distinct()->get();

        $staff_members = StaffMember::select('staff_ara_id', 'surname', 'other_names', 'department_name', 'location_in_hq', 'id')->get();
        $staff_assets_count = [];
        // foreach($staff_members as $staff_member){
        //     foreach($device_types_obj as $device_type){
        //         $staff_assets_count[$staff_member->staff_ara_id][$device_type->device_type] = $it_assets
        //             ->where('staff_ara_id', $staff_member->staff_ara_id)
        //             ->where('device_type', $device_type->device_type)
        //             ->count();
        //     }
        //     $staff_assets_count[$staff_member->staff_ara_id]['Total'] = array_sum($staff_assets_count[$staff_member->staff_ara_id]);
        // }

        // foreach($device_types_obj as $device_type){
        //     $device_types[] = $device_type->device_type;
        // }

        $data = $this->getData();
        
        return view('frontend.it_assets.assets-by-staff', compact('staff_members', 'device_types', 'it_assets', 'staff_assets_count'));
    }

    public function assetsByStaffCGPT()
    {
    // Get all IT assets grouped by staff and device type
    $staff_assets = ItAsset::select('staff_ara_id', 'device_type', DB::raw('count(*) as count'))
        ->groupBy('staff_ara_id', 'device_type')
        ->get();

    // Get distinct device types
    $device_types = $staff_assets->pluck('device_type')->unique();

    // Get staff members
    $staff_members = StaffMember::select('staff_ara_id', 'surname', 'other_names', 'department_name', 'id')->get();

    // Organize asset counts by staff and device type
    $staff_assets_count = [];
    foreach ($staff_members as $staff_member) {
        $staff_assets_count[$staff_member->staff_ara_id] = [];
        foreach ($device_types as $device_type) {
            $count = $staff_assets
                ->where('staff_ara_id', $staff_member->staff_ara_id)
                ->where('device_type', $device_type)
                ->sum('count');
            $staff_assets_count[$staff_member->staff_ara_id][$device_type] = $count;
        }
        $staff_assets_count[$staff_member->staff_ara_id]['total'] = array_sum($staff_assets_count[$staff_member->staff_ara_id]);
    }

    return view('frontend.it_assets.assets_by_staff', compact('staff_members', 'device_types', 'staff_assets_count'));
}



    public function assetsByStaffJSON(){
        $it_assets = ItAsset::all();
        $device_types = ItAsset::select('device_type')->distinct()->get();
        $staff_members = StaffMember::all();

        return response()->json(['it_assets' => $it_assets, 'staff_members' => $staff_members, 'device_types' => $device_types]);
    }

    public function staffItAssets(Request $request)
    {
        $validated = $request->validate([
            'staff_ara_id' => 'required|string|exists:staff_member_details,staff_ara_id'
        ]);

        $auth_user = auth()->user();

        if (!$auth_user->isAdmin() && !$auth_user->can('manage IT assets')) {
            return redirect()->route('frontend.index')->withErrors('Unauthorized action');
        }

        $staff = StaffMember::where('staff_ara_id', $request->staff_ara_id)->first();
        if (!$staff) {
            return back()->withErrors('No associated staff information in database');
        }

        $it_assets = ItAsset::where('staff_ara_id', $staff->staff_ara_id)->get();
        return view('frontend.it_assets.staff-it-assets')->with([
            'it_assets' => $it_assets,
            'staff' => $staff
        ]);
    }

    public function create()
    {
        return view('frontend.it_assets.create')->with([
            'data' => $this->getData()
        ]);
    }

    public function store(Request $request)
    {
        // check serial number
        if($request->filled('serial_number')) {
            $sn_count = ItAsset::where('serial_number', $request->serial_number)->count();
            if ($sn_count) {
                return [
                    'status' => 'error',
                    'message' => 'Asset with that serial number already exists in our database',
                ];
            }
        }

        $it_asset = ItAsset::create($request->all());

        if ($request->filled('asset_meta_key')) {
            $this->addAssetMeta($request, $it_asset->id);
        }

        $data['asset_meta_keys'] = DB::table('asset_meta')
            ->select('meta_key')
            ->distinct()
            ->get();
        return [
            'status' => 'success',
            'it_asset' => $it_asset,
            'asset_meta_keys' => $data['asset_meta_keys'],
        ];
    }

    public function addAssetMeta(Request $request, $asset_id)
    {
        DB::table('asset_meta')
            ->where('asset_id', $asset_id)
            ->delete();
        $now = now();
        foreach ($request->asset_meta_key as $key => $meta_key) {
            $meta_value = $request->asset_meta_value[$key];
            if (!empty($meta_key) && !empty($meta_value)) {
                $arr[] = [
                    'meta_key' => $meta_key,
                    'meta_value' => $meta_value,
                    'asset_id' => $asset_id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        if (sizeof($arr)) {
            DB::table('asset_meta')
                ->insert($arr);
        }
        return true;
    }

    public function show(ItAsset $it_asset)
    {
        return view('frontend.it_assets.show', compact('it_asset'));
    }

    public function edit(ItAsset $it_asset)
    {
        return view('frontend.it_assets.edit')->with([
            'data' => $this->getData(),
            'it_asset' => $it_asset
        ]);
    }

    public function getData()
    {
        $data['staff_members'] = StaffMember::all();
        $data['department_names'] = DB::table('staff_member_details')
            ->select('department_name')
            ->orderBy('department_name')
            ->distinct()
            ->get();

        $data['brands'] = [
            'HP', 'Dell', 'APC', 'Toshiba', 'Sony', 'Mac', 'Acer', 'Asus', 'Samsung', 'Tecno', 'iPhone', 'Xerox', 'Oppo', 'Nokia', 'iTel'
        ];

        $data['device_type'] = [
            'Laptop',
            'All In One (AIO)',
            'Desktop',
            'Tower',
            'Printer',
            'Scanner',
            'Monitor',
            'UPS',
            'Extension',
            'Other',
        ];

        $data['office_location'] = [
            'Catering'
            , 'Commercial store'
            , 'Domestic Airport'
            , 'Flight ops building'
            , 'International Airport'
            , 'Main gate'
            , 'New building'
            , 'Old building'
            , 'Outstation'
            , 'Technical hangar'
            , 'Transport maintenance'
        ];

//        $data['status'] = [
//            'in service','serviceable','nonserviceable'
//        ];

        $data['asset_meta_keys'] = DB::table('asset_meta')
            ->select('meta_key')
            ->distinct()
            ->get();

        if(!sizeof($data['asset_meta_keys'])){
            $data['asset_meta_keys'] = [
              'Processor',
              'RAM',
              'HDD',
              'WebCam'
            ];
        }

        return $data;
    }

    public function update(Request $request, ItAsset $it_asset)
    {
        if($request->filled('serial_number') && $request->serial_number != $it_asset->serial_number) {
            $sn_count = ItAsset::where('serial_number', $request->serial_number)->count();
            if ($sn_count) {
                return [
                    'status' => 'error',
                    'message' => 'Asset with that serial number already exists in our database',
                ];
            }
        }


        // store history
        $now = now();
        $asset_old['staffMember'] = $it_asset->staffMember->name;
        foreach ($it_asset->getAttributes() as $key => $value) {
            $asset_old[] = [$key => $value];
        }
        
        if ($it_asset->assetMeta->count()) {
            foreach ($it_asset->assetMeta as $assetMeta) {
                $asset_old[] = [$assetMeta->meta_key => $assetMeta->meta_value];
            }
        }

        $history = [
            'asset_id' => $it_asset->id,
            'updated_by_user_id' => auth()->id(),
            'body' => json_encode($asset_old),
            'created_at' => $now,
            'updated_at' => $now,
        ];

        DB::table('it_asset_histories')
            ->insert($history);

        $it_asset->update($request->all());

        if ($request->filled('asset_meta_key')) {
            $this->addAssetMeta($request, $it_asset->id);
        }

        return back()->withFlashSuccess('Asset updated');
    }

    public function destroy(ItAsset $it_asset)
    {
        DB::table('asset_meta')
            ->where('asset_id', $it_asset->id)
            ->delete();

        $it_asset->delete();
        return redirect()->route('frontend.it_assets.list')->withFlashInfo('Asset deleted');
    }
}
