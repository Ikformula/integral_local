<?php

namespace App\Http\Composers;

use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class FlightOpsDelayCodesComposer
{
    public function compose(View $view)
    {
        $delay_codes = DB::table('delay_codes')
            ->get();
         $view->with('delay_codes', $delay_codes);
    }
}
