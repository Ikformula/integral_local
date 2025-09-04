<?php

namespace App\Providers;

use App\Http\Composers\AircraftStatusesComposer;
use App\Http\Composers\Backend\SidebarComposer;
use App\Http\Composers\FlightOpsDelayCodesComposer;
use App\Http\Composers\GlobalComposer;
use App\Http\Composers\MenuComposer;
use App\Http\Composers\BscDailyReportComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

/**
 * Class ComposerServiceProvider.
 */
class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     */
    public function boot()
    {
        // Global
        View::composer(
            // This class binds the $logged_in_user variable to every view
            '*',
            GlobalComposer::class
        );

        // Frontend
        View::composer(
        // This class binds the $menu variable to every frontend view
            ['frontend.includes.sidebar', 'frontend.index'],
            MenuComposer::class
        );

        View::composer(
        // This class binds the $delay_codes variable to the flight ops BSC views
            ['frontend.business_goals.partials._flight-ops-operational-delivery-delays-entry', 'frontend.business_goals.add-report', 'frontend.business_goals.quadrants._3'],
            FlightOpsDelayCodesComposer::class
        );

        View::composer(
        // This class binds the aircraft status report data to the portal and BSC views
            ['frontend.aircraft_status.index', 'frontend.business_goals.quadrants._11'],
            AircraftStatusesComposer::class
        );

        View::composer(
        // This class binds the aircraft status report data to the portal and BSC views
            ['frontend.business_goals.dailies.*'],
            BscDailyReportComposer::class
        );

        // Backend
        View::composer(
            // This binds items like number of users pending approval when account approval is set to true
            'backend.includes.sidebar',
            SidebarComposer::class
        );
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        //
    }
}
