<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\CallCenterLog;
use App\Models\HrAdvisor;
use App\Models\Setting;
use App\Models\StaffTravelBooking;
use App\Models\StaffTravelBeneficiary;
use App\Models\StaffMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StaffTravelFormerController extends Controller
{
    public function index(Request $request)
    {
        // Determine date range
        $startDate = $request->input('start_date')
            ? Carbon::parse($request->input('start_date'))->startOfDay()
            : Carbon::now()->startOfYear();
        $endDate = $request->input('end_date')
            ? Carbon::parse($request->input('end_date'))->endOfDay()
            : Carbon::now();

        // Recent bookings
        $staff_travel_bookings = StaffTravelBooking::whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->take(10)
            ->get();

        // Stats calculations
        $number_of_bookings = StaffTravelBooking::whereBetween('created_at', [$startDate, $endDate])->count();
        $total_beneficiaries = StaffTravelBeneficiary::count();
        $pending_beneficiaries = StaffTravelBeneficiary::where('status', 'pending')->count();
        $total_staff = StaffMember::count();
        $unique_staff_with_bookings = StaffTravelBooking::distinct('staff_ara_id')->count('staff_ara_id');
        $platform_utilization_percentage = $total_staff > 0
            ? round(($unique_staff_with_bookings / $total_staff) * 100, 2)
            : 0;

        // Build stats array for dashboard widgets
        $stats = [
            [
                'icon'  => 'fas fa-plane',
                'title' => 'Bookings (Selected Period)',
                'value' => $number_of_bookings,
            ],
            [
                'icon'  => 'fas fa-users',
                'title' => 'Total Beneficiaries',
                'value' => $total_beneficiaries,
            ],
            [
                'icon'  => 'fas fa-hourglass-half',
                'title' => 'Pending Beneficiaries',
                'value' => $pending_beneficiaries,
            ],
            [
                'icon'  => 'fas fa-percentage',
                'title' => 'Platform Utilization (%)',
                'value' => $platform_utilization_percentage . '%',
            ],
        ];

        // Ranks by department join
        $ranks_by_department = DB::table('staff_travel_bookings')
            ->join('staff_member_details', 'staff_travel_bookings.staff_ara_id', '=', 'staff_member_details.staff_ara_id')
            ->select('staff_member_details.department_name', DB::raw('COUNT(*) as total'))
            ->whereBetween('staff_travel_bookings.created_at', [$startDate, $endDate])
            ->groupBy('staff_member_details.department_name')
            ->orderByDesc('total')
            ->get();

        // Bookings by month for graph
        $booking_by_month = StaffTravelBooking::whereBetween('created_at', [$startDate, $endDate])
            ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'), DB::raw('COUNT(*) as total'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Return to view
        return view('frontend.staff_travel.index', compact(
            'staff_travel_bookings',
            'stats',
            'ranks_by_department',
            'booking_by_month',
            'startDate',
            'endDate'
        ));
    }
    public function bookings()
    {
        $user = Auth::user();

        if ($user->can('manage staff travel portal')) {
            $staff_travel_bookings = StaffTravelBooking::latest()->paginate();
        } else {
            return redirect()->route('frontend.staff_travel.my_bookings');
        }
        return view('frontend.staff_travel.bookings', compact('staff_travel_bookings'));
    }

    public function routeForFailure($msg)
    {
        return redirect()->route('frontend.index')->withErrors($msg);
    }

    public function staffTravelPortal()
    {
        $auth_user = auth()->user();
        $staff_member = $auth_user->staff_member;
        if(!isset($staff_member))
            return $this->routeForFailure('Not a staff member');

        $yearly_booking_allowance = Setting::where('category', 'staff_travel_portal')
            ->where('key', 'yearly_booking_allowance')
            ->first();

        $now = Carbon::now();
        $staff_ara_id = $staff_member->staff_ara_id;
        $years_bookings = StaffTravelBooking::where('staff_ara_id', $staff_ara_id)
            ->where('request_year', $now->year)
            ->get();
//        $years_bookings_count = $years_bookings->sum('adult') + $years_bookings->sum('child');
        $years_bookings_count = $years_bookings->count();

        $stats['Bookings This Year'] = [
          'title' => 'Bookings This Year',
            'value' => $years_bookings_count,
            'icon' => 'book-open'
        ];

        $stats['Bookings Left']['title'] = 'Bookings Left for This Year';

        $stats['Bookings Left']['value'] = $yearly_booking_allowance->value - $stats['Bookings This Year']['value'];
        $stats['Bookings Left']['icon'] = 'book-open';

        $staff_travel_bookings = StaffTravelBooking::where('staff_ara_id', $staff_ara_id)->take(10)->get();

        return view('frontend.staff_travel.staff_travel_portal')->with([
            'stats' => $stats,
            'staff_travel_bookings' => $staff_travel_bookings
        ]);
    }

    public function myBookings()
    {
        $auth_user = auth()->user();
        $staff_member = $auth_user->staff_member;
        if(!isset($staff_member))
            return $this->routeForFailure('Not a staff member');

        $staff_travel_bookings = StaffTravelBooking::where('staff_ara_id', $staff_member->staff_ara_id)->latest()->paginate();
        return view('frontend.staff_travel.bookings', compact('staff_travel_bookings'));
    }

    public function makeBooking()
    {
        $tomorrow = Carbon::tomorrow();
        return view('frontend.staff_travel.make_booking')->with([
            'tomorrow' => $tomorrow
        ]);
    }

    public function processBooking()
    {

    }


}
