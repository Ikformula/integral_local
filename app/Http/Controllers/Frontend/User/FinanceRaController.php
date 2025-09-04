<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FinanceRa;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FinanceRaController extends Controller
{
    public $columns;

    public function __construct(){
        $this->columns = [
            'charter' => 'CHARTER',
            'cargo_docum' => 'CARGO DOCUM.',
            'cargo' => 'CARGO',
            'corpgrpcall_centre_sales' => 'CORP/GRP/CALL CENTRE SALES',
            'travel_agents' => 'TRAVEL AGENTS',
            'paystack' => 'PAYSTACK',
            'interswitch_sales' => 'INTERSWITCH SALES',
            'web_salesteflon' => 'WEB SALES-TEFLON',
            'boh' => 'BOH',
            'mobile_sales' => 'MOBILE SALES',
            'gtpayment' => 'GT-PAYMENT',
            'gladepay' => 'GLADEPAY',
            'excess_bag' => 'EXCESS BAG',
            'penalty' => 'PENALTY',
            'domestic' => 'DOMESTIC',
            'ibom_air' => 'IBOM AIR',
            'zenith_bank_gulf_stream' => 'ZENITH BANK GULF STREAM',
            'syphax' => 'SYPHAX',
            'aiico_travel_insurance' => 'AIICO TRAVEL INSURANCE',
            'allied_air_ltd' => 'ALLIED AIR LTD',
            'ffv' => 'FFV',
            'songhai_aviation' => 'SONGHAI AVIATION',
            'green_africa' => 'GREEN AFRICA',
            'avmax_aircraft_leasing_incorp' => 'AVMAX AIRCRAFT LEASING INCORP.',
            'middle_east_airlinesair_libran' => 'MIDDLE EAST AIRLINES-AIR LIBRAN',
            'air_peace_ltd' => 'AIR PEACE LTD',
            'asky_aircraft_maintengr' => 'ASKY AIRCRAFT MAINT.&ENGR.',
            'clobekadmin_charges' => 'CLOBEK-admin charges',
            'eznis' => 'EZNIS',
            'qatar' => 'QATAR',
            'aero_airline' => 'AERO AIRLINE',
            'cwt_agent' => 'CWT AGENT',
            'ezuma_jeta' => 'EZUMA_JETA',
            'austamarc_intl' => 'AUSTAMARC INTL',
            'azman' => 'AZMAN',
            'gatelesis' => 'GA-TELESIS',
            'galatic' => 'GALATIC',
            'galaticloc' => 'GALATIC-LOC',
            'heston_mro_europe' => 'HESTON MRO EUROPE',
            'kalabash_technology_solution_ltd' => 'KALABASH TECHNOLOGY SOLUTION LTD',
            'ng_eagle' => 'NG EAGLE',
            'seven_star_global_hangar' => '7STAR GLOBAL HANGAR',
            'dana_air' => 'DANA AIR',
            'pieces_aviation' => 'PIECES AVIATION',
            'rwandair' => 'RWANDAIR',
            'hajj_operations' => 'HAJJ OPERATIONS',
            'sierra_leone_caa' => 'SIERRA LEONE CAA',
            'dornier_aviation_nigeria' => 'DORNIER AVIATION NIGERIA',
            'united_nigeria' => 'UNITED NIGERIA',
            'skycare' => 'SKYCARE',
            'maxair' => 'MAXAIR',
            'pax_service_fee' => 'Pax Service fee',
            'travel_insurance' => 'Travel Insurance',
            'clobekbase_fare' => 'Clobek-Base fare',
            'clobekyq_surcharge' => 'Clobek-YQ (Surcharge)',
            'just_usbase_fare' => 'Just US-Base fare',
            'just_usyq_surcharge' => 'Just US-YQ (Surcharge)',
            'refunddomestic_tsr' => 'Refund-Domestic (TSR)',
            'refundonline_web' => 'Refund-Online (Web)',
            'ng_tax_5tsc' => 'NG Tax (5%TSC)',
            'qt_faan' => 'QT (FAAN)',
            'higher_standards_aerospace' => 'HIGHER STANDARDS AEROSPACE',
            'goal_energy' => 'GOAL ENERGY',
            'others' => 'OTHERS',
            'flownrevarik' => 'Flown-Rev-Arik',
            'flownrevgalatic' => 'Flown-Rev-Galatic',
            'expired_tickets' => 'Expired Tickets'
        ];
    }

    public function index()
    {
        $finance_ras = FinanceRa::orderBy('id', 'DESC')->take(100)->get()->reverse();
        $columns = $this->columns;
        return view('frontend.finance_ras.index', compact('finance_ras', 'columns'));
    }

    public function store(Request $request)
    {
        $log = FinanceRa::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Record created successfully',
            'new_log' => $log
        ], 201);
    }

    public function update(Request $request)
    {
        // Retrieve the original data
        $log = FinanceRa::findOrFail($request->log_id);
        $originalData = $log->getOriginal();

        // Update the record
        $log->update($request->all());

        // Compare the original data with the updated data
        $updatedData = $log->getAttributes();
        $changedColumns = array_diff_assoc($updatedData, $originalData);

        // Record the history of each changed column
        foreach ($changedColumns as $column => $newValue) {
            if ($column !== 'updated_at') {  // Skip the updated_at column
                DB::table('finance_ra_histories')->insert([
                    'column_name' => $column,
                    'finance_ra_id' => $log->id,
                    'former_value' => $originalData[$column] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Record updated successfully',
            'log' => $log
        ], 200);
    }

    public function destroy($id)
    {
        $log = FinanceRa::findOrFail($id);
        $log->delete();

        return response()->json([
            'success' => true,
            'message' => 'Record deleted successfully'
        ], 200);
    }

}

