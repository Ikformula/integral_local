<?php


namespace App\Http\Composers;


use App\Models\Pilot;
use App\Models\ServiceNowGroup;
use App\Models\ServiceNowGroupAgent;
use App\Models\StaffMember;
use App\Models\Vacancy;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MenuComposer
{
    /**
     * Bind data to the view.
     *
     * @param View $view
     */
    public function compose(View $view)
    {
        if (Auth::check()) {
            $user = auth()->user();
            $staff_member = $user->staff_member;
            $staff_ara_id = $staff_member ? $staff_member->staff_ara_id : null;
            $user_is_admin = $user->isAdmin();
            $user_is_ecs_client = $user->isEcsClient ? $user->isEcsClient->count() : false;
            $menus = array();
            $menu_group = [];

            //            if(config('app.env') == 'local') {
            if (1 < 0) {
                if ($user->hasRole('call center admin') || $user->hasRole('call center agent') || $user_is_admin) {
                    unset($menu_group);
                    unset($dept_menu);
                    $dept_menu = array();
                    if ($user->hasRole('call center admin') || $user_is_admin) {
                        array_push($dept_menu, [
                            'title' => 'CRM Dashboard',
                            //                        'link' => route('frontend.call_center.index'),
                            'link' => "https://lookerstudio.google.com/embed/reporting/83e0e8da-e297-4197-ac93-88989a23224e/page/TV4ED",
                            'icon' => 'fas fa-tachometer-alt'
                        ]);
                    }

                    if ($user->can('create call log')) {
                        array_push($dept_menu, [
                            'title' => 'Enter A Log',
                            'link' => route('frontend.call_center.create.log'),
                            'icon' => 'far fa-list-alt'
                        ]);
                    }

                    array_push($dept_menu, [
                        'title' => 'View Logs',
                        'link' => route('frontend.call_center.logs'),
                        'icon' => 'fas fa-list-ol'
                    ]);

                    $menu_group = [
                        'title' => 'ArikCRM',
                        'link' => '#call-center',
                        'icon' => 'fas fa-headset',
                        'group' => 'call-center',
                        'links' => $dept_menu
                    ];

                    array_push($menus, $menu_group);
                }


                //              HMO start
                unset($menu_group);
                unset($dept_menu);
                $dept_menu = array();
                if ($user->can('update other staff info')) {
                    array_push($dept_menu, [
                        'title' => 'Staff Members List',
                        'link' => route('frontend.hmo.staff_member.index'),
                        'icon' => 'far fa-list-alt'
                    ]);

                    array_push($dept_menu, [
                        'title' => 'Family Members List',
                        'link' => route('frontend.hmo.family_members'),
                        'icon' => 'far fa-list-alt'
                    ]);
                }

                if ($staff_member) {
                    array_push($dept_menu, [
                        'title' => 'My Data',
                        'link' => route('frontend.hmo.show.staff_member', $staff_member->staff_ara_id),
                        'icon' => 'fas fa-list-ol'
                    ]);
                }

                $menu_group = [
                    'title' => 'ArikHMO',
                    'link' => '#hmo',
                    'icon' => 'fas fa-clinic-medical',
                    'group' => 'hmo',
                    'links' => $dept_menu
                ];

                array_push($menus, $menu_group);
                //                HMO end
            } // env check


            if ($user->hasRole('hr advisor') || $user_is_admin || (isset($staff_member) && ($staff_member->department_name == 'HR' || in_array($staff_ara_id, ['6878'])))) {
                //              Staff Travel start
                // Staff Travel Management
                unset($menu_group);
                unset($dept_menu);
                $dept_menu = array();
                if ($user->can('manage staff travel portal') || $user->hasRole('hr advisor')) {
                    array_push($dept_menu, [
                        'title' => 'Dashboard',
                        'link' => route('frontend.staff_travel.index'),
                        'icon' => 'fas fa-tachometer-alt'
                    ]);

                    array_push($dept_menu, [
                        'title' => 'Pending Beneficiaries',
                        'link' => route('frontend.staff_travel_beneficiaries.pending'),
                        'icon' => 'fas fa-list-alt',
                    ]);

                    array_push($dept_menu, [
                        'title' => 'All Beneficiaries',
                        'link' => route('frontend.staff_travel_beneficiaries.index'),
                        'icon' => 'fas fa-list-alt',
                    ]);

                    array_push($dept_menu, [
                        'title' => 'Bookings',
                        'link' => route('frontend.staff_travel.bookings'),
                        'icon' => 'far fa-list-alt'
                    ]);

                    if ($user->can('manage staff travel portal')) {
                        array_push($dept_menu, [
                            'title' => 'HR Settings',
                            'link' => route('frontend.staff_travel.stbHrMGT'),
                            'icon' => 'fas fa-gear',
                        ]);
                    }

                    $menu_group = [
                        'title' => 'Staff Travel Mgt.',
                        'link' => '#hmo',
                        'icon' => 'fas fa-plane',
                        'group' => 'hmo',
                        'links' => $dept_menu
                    ];

                    array_push($menus, $menu_group);
                }

                if ($staff_member) {
                    unset($menu_group);
                    unset($dept_menu);
                    $dept_menu = array();
                    array_push($dept_menu, [
                        'title' => 'My Staff Travel Portal',
                        'link' => route('frontend.staff_travel.staff_travel_portal'),
                        'icon' => 'fas fa-plane'
                    ]);

                    array_push($dept_menu, [
                        'title' => 'Beneficiaries',
                        'link' => route('frontend.staff_travel_beneficiaries.index.mine'),
                        'icon' => 'fas fa-list-alt',
                    ]);

                    array_push($dept_menu, [
                        'title' => 'Make Booking',
                        'link' => route('frontend.staff_travel.make_booking'),
                        'icon' => 'fas fa-book-open'
                    ]);

                    array_push($dept_menu, [
                        'title' => 'My Bookings',
                        'link' => route('frontend.staff_travel.my_bookings'),
                        'icon' => 'fas fa-plane-departure'
                    ]);
                    $menu_group = [
                        'title' => 'Staff Travel',
                        'link' => '#hmo',
                        'icon' => 'fas fa-plane',
                        'group' => 'hmo',
                        'links' => $dept_menu
                    ];

                    array_push($menus, $menu_group);
                }


                //                Staff Travel end
            } // HR testing check end



            //                Staff Attendance Start
            unset($menu_group);
            unset($dept_menu);
            $dept_menu = array();
            if ($user->can('manage all staff attendance') || $user->can('manage own unit info')) {
                array_push($dept_menu, [
                    'title' => 'Backend',
                    'link' => route('frontend.attendance.multiple.staff.attendance'),
                    'icon' => 'fas fa-tachometer-alt'
                ]);

                array_push($dept_menu, [
                    'title' => 'Summaries',
                    'link' => route('frontend.attendance.staff.attendance.summaries'),
                    'icon' => 'fas fa-list'
                ]);

                if (1 < 0) {
                    array_push($dept_menu, [
                        'title' => 'Holidays',
                        'link' => route('frontend.attendance.holidays.all'),
                        'icon' => 'fas fa-list-alt'
                    ]);
                }

                //                array_push($dept_menu, [
                //                    'title' => 'Attendance Test',
                //                    'link' => route('frontend.attendance.view.individual.staff').'?staff_ara_id=6575',
                //                    'icon' => 'far fa-list-alt'
                //                ]);
            }

            if ($staff_member) {
                array_push($dept_menu, [
                    'title' => 'My Attendance',
                    'link' => route('frontend.attendance.my.attendance') . '?staff_ara_id=' . $staff_member->staff_ara_id,
                    'icon' => 'far fa-list-alt'
                ]);

                $count_underlings = StaffMember::where('manager_ara_id', $staff_member->staff_ara_id)->count();
                if ($count_underlings) {
                    array_push($dept_menu, [
                        'title' => 'Managed Staff',
                        'link' => route('frontend.attendance.managed.staff'),
                        'icon' => 'fas fa-users'
                    ]);
                    array_push($dept_menu, [
                        'title' => 'Created Authorizations',
                        'link' => route('frontend.attendance.managed.authorizations'),
                        'icon' => 'fas fa-list-alt'
                    ]);
                    array_push($dept_menu, [
                        'title' => 'Add Authorization',
                        'link' => route('frontend.attendance.create.manager.authorization'),
                        'icon' => 'fas fa-signature'
                    ]);
                }
            }

            if ($dept_menu) {
                $menu_group = [
                    'title' => 'Staff Attendance',
                    'link' => '#attendance',
                    'icon' => 'fas fa-check',
                    'group' => 'attendance',
                    'links' => $dept_menu
                ];

                array_push($menus, $menu_group);
            }
            //                Staff Attendance End


            //              Pilot eLibrary start
            if ($user->can('view Q400 PDFs') || $user->can('view 737 PDFs')) {
                unset($menu_group);
                unset($dept_menu);
                $dept_menu = array();

                if ($user->can('view Q400 PDFs')) {
                    array_push($dept_menu, [
                        'title' => 'Q400 Files',
                        'link' => route('frontend.pilotLibrary.index') . '?fleet=Q400',
                        'icon' => 'fas fa-list-alt'
                    ]);
                }

                if ($user->can('view 737 PDFs')) {
                    array_push($dept_menu, [
                        'title' => '737 Files',
                        'link' => route('frontend.pilotLibrary.index') . '?fleet=737',
                        'icon' => 'fas fa-list-alt'
                    ]);
                }

                if ($user->can('manage pilot elibrary')) {
                    array_push($dept_menu, [
                        'title' => 'Add Document',
                        'link' => route('frontend.pilotLibrary.create'),
                        'icon' => 'far fa-list-alt'
                    ]);
                }


                $menu_group = [
                    'title' => 'Flight Crew Documents',
                    'link' => '#hmo',
                    'icon' => 'fas fa-d',
                    'group' => 'flight_crew',
                    'links' => $dept_menu
                ];

                array_push($menus, $menu_group);
            }
            //                Pilot eLibrary end


            //              Business Goals start
            if ($user->can('manage business goals data')) {
                unset($menu_group);
                unset($dept_menu);
                $dept_menu = array();
                if ($user->can('see all business score cards') || count($user->accessibleBusinessAreas()) > 1) {
                    array_push($dept_menu, [
                        'title' => 'Business Score Cards',
                        'link' => route('frontend.business_goals.multi.business.areas'),
                        'icon' => 'fas fa-business-time'
                    ]);
                }

                if ($user_is_admin || !$user->can('see all business score cards')) {  // CEO doesn't need to see these
                    array_push($dept_menu, [
                        'title' => 'Business Areas',
                        'link' => route('frontend.business_goals.business.areas'),
                        'icon' => 'fas fa-business-time'
                    ]);

                    if (!$user->can('manage aircraft status data') || $user_is_admin) {
                        array_push($dept_menu, [
                            'title' => 'Data Entry',
                            'link' => route('frontend.business_goals.add_report'),
                            'icon' => 'fa fa-pen-alt'
                        ]);

                        //                        if($user_is_admin){
                        array_push($dept_menu, [
                            'title' => 'Single Day Data Entry',
                            'link' => route('frontend.business_goals.add_single_day_report'),
                            'icon' => 'fa fa-pen-alt'
                        ]);
                        //                        }
                    }
                }

                if ($user_is_admin) {
                    array_push($dept_menu, [
                        'title' => 'Form Fields',
                        'link' => route('frontend.business_goals.form_fields.index'),
                        'icon' => 'fa fa-pen-alt'
                    ]);
                }

                if ($user->can('manage aircraft status data')) {
                    array_push($dept_menu, [
                        'title' => 'Aircraft Status Data Entry',
                        'link' => route('frontend.aircraft_status.index'),
                        'icon' => 'fa fa-plane-up'
                    ]);

                    array_push($dept_menu, [
                        'title' => 'Aircraft Status Report',
                        'link' => route('frontend.business_goals.single.daily.quadrant') . '?business_area_id=11',
                        'icon' => 'fa fa-plane-up'
                    ]);
                }

                $menu_group = [
                    'title' => 'Business Goals',
                    'link' => '#business_goals',
                    'icon' => 'fas fa-bullseye',
                    'group' => 'business_goals',
                    'links' => $dept_menu
                ];

                array_push($menus, $menu_group);
            }
            //                Business Goals  end


            if ($user->can('can enter icu activities')) {
                array_push($menus, [
                    'title' => 'ICU Activities',
                    'link' => route('frontend.icu_activities.index'),
                    'icon' => 'fas fa-list',
                    'sidebar_only' => false
                ]);

                array_push($menus, [
                    'title' => 'Exchange Rates',
                    'link' => route('frontend.exchange_rates.index'),
                    'icon' => 'fas fa-list',
                    'sidebar_only' => false
                ]);
            }

            if (!$user_is_ecs_client && !$user->hasRole('external lawyer') && ($staff_member || $user_is_admin)) {
                array_push($menus, [
                    'title' => 'AVSEC Vehicle Registry',
                    'link' => route('frontend.avsec_vehicles.index'),
                    'icon' => 'fas fa-car',
                    'sidebar_only' => false
                ]);
            }

            //              Tour Operations start
            if ($user->can('make tour bookings')) {
                unset($menu_group);
                unset($dept_menu);
                $dept_menu = array();

                array_push($dept_menu, [
                    'title' => 'Passengers',
                    'link' => route('frontend.tour_operations.passengers.list'),
                    'icon' => 'fas fa-list-alt'
                ]);

                //                array_push($dept_menu, [
                //                    'title' => 'My Opened Tabs',
                //                    'link' => route('frontend.tour_operations.passengers.my.opened.list'),
                //                    'icon' => 'fas fa-list-alt'
                //                ]);

                array_push($dept_menu, [
                    'title' => 'Completed Bookings',
                    'link' => route('frontend.tour_operations.passengers.completed.bookings'),
                    'icon' => 'fas fa-list-alt'
                ]);

                if ($user->can('view tour booking performance')) {
                    array_push($dept_menu, [
                        'title' => 'TROs Management',
                        'link' => route('frontend.tour_operations.tros.index'),
                        'icon' => 'fas fa-users'
                    ]);
                }

                $menu_group = [
                    'title' => 'Tour Operations',
                    'link' => '#tour-operations',
                    'icon' => 'fas fa-plane-up',
                    'group' => 'tour_operations',
                    'links' => $dept_menu
                ];

                array_push($menus, $menu_group);
            }
            //                Tour Operations end


            //              IT Assets start
            if ($user->can('manage IT assets')) {
                unset($menu_group);
                unset($dept_menu);
                $dept_menu = array();

                array_push($dept_menu, [
                    'title' => 'IT Assets Dashboard',
                    'link' => route('frontend.it_assets.dashboard'),
                    'icon' => 'fas fa-list-alt'
                ]);

                array_push($dept_menu, [
                    'title' => 'All IT Assets',
                    'link' => route('frontend.it_assets.list'),
                    'icon' => 'fas fa-list-alt'
                ]);

                array_push($dept_menu, [
                    'title' => 'Register IT Asset',
                    'link' => route('frontend.it_assets.create'),
                    'icon' => 'fas fa-laptop-file'
                ]);

                array_push($dept_menu, [
                    'title' => 'Staff Assets Count',
                    'link' => route('frontend.it_assets.assetsByStaff'),
                    'icon' => 'fas fa-list-alt'
                ]);


                $menu_group = [
                    'title' => 'IT Assets',
                    'link' => '#it-assets',
                    'icon' => 'fas fa-computer',
                    'group' => 'it_assets',
                    'links' => $dept_menu
                ];

                array_push($menus, $menu_group);
            }
            //                IT Assets end


            // ********* Network outage analysis tool Start
            if ($user->can('analyze network outage logs')) {
                array_push($menus, [
                    'title' => 'Network Outage Analysis',
                    'link' => route('frontend.network.outage.analysis.tool'),
                    'icon' => 'fas fa-tachometer-alt',
                    'sidebar_only' => false
                ]);
            }
            // ********* Network outage analysis tool End


            // *******

            if ($user_is_admin) {
                array_push($menus, [
                    'title' => 'Admin Dashboard',
                    'link' => route('admin.dashboard'),
                    'icon' => 'fas fa-tachometer-alt',
                    'sidebar_only' => true,
                    'attributes' => 'hx-boost="unset"'
                ]);

                array_push($menus, [
                    'title' => 'Activity Logs',
                    'link' => route('frontend.user.activity.index'),
                    'icon' => 'fas fa-list-alt',
                    'sidebar_only' => false,
                    'attributes' => 'hx-boost="unset"'
                ]);

                array_push($menus, [
                    'title' => 'Email Logs',
                    'link' => route('frontend.outgoingMessages'),
                    'icon' => 'fas fa-list-alt',
                    'sidebar_only' => false,
                    'attributes' => 'hx-boost="unset"'
                ]);
            }

            // Staff Management start
            unset($menu_group);
            unset($dept_menu);
            $dept_menu = array();
            if ($user->can('manage own unit info') || $user->can('update other staff info')) {
                array_push($dept_menu, [
                    'title' => 'Staff Members Profiles',
                    'link' => route('frontend.user.profiles'),
                    'icon' => 'fas fa-address-card',
                    'attributes' => 'hx-boost="unset"'
                ]);

                array_push($dept_menu, [
                    'title' => 'Add Staff Member',
                    'link' => route('frontend.staff_info_management.createStaffForm'),
                    'icon' => 'fas fa-user-plus',
                ]);

                if ($user->can('edit staff email')) {
                    array_push($dept_menu, [
                        'title' => 'Staff Members\' Emails',
                        'link' => route('frontend.staff_info_management.emailFix'),
                        'icon' => 'fas fa-envelope',
                        'sidebar_only' => false
                    ]);

                    array_push($dept_menu, [
                        'title' => 'MS Exchange Emails',
                        'link' => route('frontend.staff_info_management.ms.email'),
                        'icon' => 'fas fa-envelope',
                        'sidebar_only' => false
                    ]);
                }

                $menu_group = [
                    'title' => 'Staff Management',
                    'link' => '#staff-members-mgt',
                    'icon' => 'fas fa-users',
                    'group' => 'staff_members_mgt',
                    'links' => $dept_menu
                ];

                array_push($menus, $menu_group);
            }
            // Staff Management end
            if (!$user_is_ecs_client && !$user->hasRole('external lawyer')) {
                array_push($menus, [
                    'title' => 'CUG Line',
                    'link' => route('frontend.cug_lines.index'),
                    'icon' => 'fas fa-phone',
                    'sidebar_only' => false
                ]);
            }

            // Safety Review Board start
            unset($menu_group);
            unset($dept_menu);
            $dept_menu = array();
            if ($user->can('manage safety performance index') || $user->can('enter SPI data')) {
                if ($user_is_admin) {
                    array_push($dept_menu, [
                        'title' => 'Sectors Preview',
                        'link' => route('frontend.srb.sectors'),
                        'icon' => 'fas fa-building',
                        'attributes' => 'hx-boost="unset"'
                    ]);
                }

                if ($user->can('manage safety performance index')) {
                    array_push($dept_menu, [
                        'title' => 'User Access',
                        'link' => route('frontend.safety_review.permissions.index'),
                        'icon' => 'fas fa-users',
                        'attributes' => 'hx-boost="unset"'
                    ]);

                    array_push($dept_menu, [
                        'title' => 'Metric Targets',
                        'link' => route('frontend.safety_review.targets.index'),
                        'icon' => 'fas fa-bullseye',
                    ]);

                    array_push($dept_menu, [
                        'title' => 'Formulae',
                        'link' => route('frontend.safety_review.formulae.index'),
                        'icon' => 'fas fa-envelope',
                        'sidebar_only' => false
                    ]);

                    array_push($dept_menu, [
                        'title' => 'View Year\'s Report',
                        'link' => route('frontend.safety_review.report.year.selection'),
                        'icon' => 'fas fa-calendar',
                        'sidebar_only' => false
                    ]);
                }

                array_push($dept_menu, [
                    'title' => 'Report Entry',
                    'link' => route('frontend.safety_review.report.entry'),
                    'icon' => 'fas fa-envelope',
                    'sidebar_only' => false
                ]);

                $menu_group = [
                    'title' => 'Safety Review',
                    'link' => '#safety-review',
                    'icon' => 'fas fa-shield-halved',
                    'group' => 'safety_review',
                    'links' => $dept_menu
                ];

                array_push($menus, $menu_group);
            }
            // Safety Review Board end


            // QA Letters start
            unset($menu_group);
            unset($dept_menu);
            $dept_menu = array();
            if ($user->can('manage qa letters')) {

                array_push($dept_menu, [
                    'title' => 'Letters',
                    'link' => route('frontend.qa_letter.index'),
                    'icon' => 'fas fa-envelope',
                    'sidebar_only' => false
                ]);

                array_push($dept_menu, [
                    'title' => 'Categories',
                    'link' => route('frontend.qa_categories.index'),
                    'icon' => 'fas fa-list-alt',
                    'sidebar_only' => false
                ]);

                $menu_group = [
                    'title' => 'QA Letters',
                    'link' => '#qa-letters',
                    'icon' => 'fas fa-shield-halved',
                    'group' => 'qa-letters',
                    'links' => $dept_menu
                ];

                array_push($menus, $menu_group);
            }
            // QA Letters end


            if ($user->can('update other staff info')) {
                array_push($menus, [
                    'title' => 'Staff Records - HR',
                    'link' => route('frontend.user.staff.index'),
                    'icon' => 'fas fa-list-alt',
                    'sidebar_only' => false
                ]);
            }

            // Careers start
            unset($menu_group);
            unset($dept_menu);
            $dept_menu = array();
            if ($user->can('manage vacancy postings')) {
                array_push($dept_menu, [
                    'title' => 'Vacancies Backend',
                    'link' => route('frontend.vacancies.backend.index'),
                    'icon' => 'fas fa-list-alt',
                    'attributes' => 'hx-boost="unset"'
                ]);
            }

            if ($staff_member) {
                $nonAppliedVacancies = $staff_member->nonAppliedVacancies();
                if ($nonAppliedVacancies)
                    $vacancies_count = $nonAppliedVacancies->count();

                array_push($dept_menu, [
                    'title' => 'Open Vacancies',
                    'link' => route('frontend.job_applications.vacancies'),
                    'icon' => 'fas fa-list-alt',
                    'badge_colour' => 'success',
                    'badge_text' => isset($vacancies_count) && $vacancies_count != 0 ? $vacancies_count : null,
                ]);
            }

            if (!$user_is_ecs_client && !$user->hasRole('external lawyer')) {
                $all_vacancies_count = Vacancy::where('date_advertised', '<=', now())
                    ->where('date_of_closing', '>=', now())->count();
                $menu_group = [
                    'title' => 'Careers',
                    'link' => '#vacancies',
                    'icon' => 'fas fa-briefcase',
                    'group' => 'vacancies',
                    'links' => $dept_menu,
                    'badge_colour' => 'warning',
                    'badge_text' => isset($all_vacancies_count) && $all_vacancies_count != 0 ? $all_vacancies_count : null,
                ];

                array_push($menus, $menu_group);
            }
            // Careers end


            if ($staff_member) {
                array_push($menus, [
                    'title' => 'ID Card/Staff Info Update',
                    'link' => route('frontend.user.profile.editIDcard') . '?staff_ara_id=' . $staff_member->staff_ara_id,
                    'icon' => 'fas fa-address-card',
                    'sidebar_only' => false
                ]);

                //                array_push($menus, [
                //                    'title' => 'Condolences',
                //                    'link' => route('frontend.occasions.show', 'francis-okafor-1'),
                //                    'icon' => 'fas fa-pen-alt',
                //                    'sidebar_only' => false
                //                ]);

                //                array_push($menus, [
                //                    'title' => 'Change Staff Travel Password',
                //                    'link' => route('frontend.staff_travel.reset_password_email'),
                //                    'icon' => 'fas fa-lock',
                //                    'sidebar_only' => false
                //                ]);

                if ($user->can('view workflows')) {
                    array_push($menus, [
                        'title' => 'Workflow Demo',
                        'link' => route('frontend.work_flows.workflow.index'),
                        'icon' => 'fas fa-address-card',
                        'sidebar_only' => false
                    ]);
                }
            }

            if ($user->can('enter logkeeps') || $user->can('view logstreams')) {
                array_push($menus, [
                    'title' => 'ERP-CMC Logkeeper',
                    'link' => route('frontend.log_keeping.erps'),
                    'icon' => 'fas fa-list-alt',
                    'sidebar_only' => false
                ]);
            }


            // ECS start
            unset($menu_group);
            unset($dept_menu);
            $dept_menu = array();
            if ($user->can('manage ecs processes') || $user->can('manage ecs client balances') || $user_is_admin) {
                if ($user->can('manage ecs processes')) {
                    array_push($dept_menu, [
                        'title' => 'Dashboard',
                        'link' => route('frontend.ecs.dashboard'),
                        'icon' => 'fas fa-qrcode',
                    ]);

                    array_push($dept_menu, [
                        'title' => 'Manual Guide',
                        'link' => route('frontend.ecsManual'),
                        'icon' => 'fas fa-book',
                    ]);

                    if ($user->can('view ecs activity logs')) {
                        array_push($dept_menu, [
                            'title' => 'Manage Access',
                            'link' => route('frontend.ecs_portal_users.index'),
                            'icon' => 'fas fa-list-alt',
                        ]);

                        array_push($dept_menu, [
                            'title' => 'Activity Logs',
                            'link' => route('frontend.ecs.activities.log'),
                            'icon' => 'fas fa-chart-simple',
                        ]);

                        array_push($dept_menu, [
                            'title' => 'Reports',
                            'link' => route('frontend.ecs.timely.reports'),
                            'icon' => 'fas fa-table',
                        ]);
                    }

                    array_push($dept_menu, [
                        'title' => 'Make Trx Request',
                        'link' => route('frontend.ecs_bookings.create'),
                        'icon' => 'fas fa-ticket',
                    ]);
                    // ECS Transaction Request Menus
                    array_push($dept_menu, [
                        'title' => 'View Requests',
                        'link' => route('frontend.ecs_flight_transactions.index', ['filter' => 'view']),
                        'icon' => 'fas fa-eye',
                    ]);
                    array_push($dept_menu, [
                        'title' => 'Refunds',
                        'link' => route('frontend.ecs_flight_transactions.index', ['filter' => 'refunds']),
                        'icon' => 'fas fa-rotate-left',
                    ]);

                    array_push($dept_menu, [
                        'title' => 'Add Refunds',
                        'link' => route('frontend.ecs_refunds.createGroupRefunds'),
                        'icon' => 'fas fa-circle-left',
                    ]);

//                    array_push($dept_menu, [
//                        'title' => 'Push To Reconciliation',
//                        'link' => route('frontend.ecs_flight_transactions.index', ['filter' => 'push_to_reconciliation']),
//                        'icon' => 'fas fa-arrow-right-arrow-left',
//                    ]);
//                    array_push($dept_menu, [
//                        'title' => 'Reverse Requests',
//                        'link' => route('frontend.ecs_flight_transactions.index', ['filter' => 'reverse']),
//                        'icon' => 'fas fa-undo',
//                    ]);

                    array_push($dept_menu, [
                        'title' => 'Verify Requests',
                        'link' => route('frontend.ecs_flight_transactions.index', ['filter' => 'verify']),
                        'icon' => 'fas fa-user-check',
                    ]);

//                    array_push($dept_menu, [
//                        'title' => 'Disapproved Requests',
//                        'link' => route('frontend.ecs_flight_transactions.index', ['filter' => 'disapproved']),
//                        'icon' => 'fas fa-ban',
//                    ]);

//                    array_push($dept_menu, [
//                        'title' => 'Push To Client',
//                        'link' => route('frontend.ecs_flight_transactions.index', ['filter' => 'push_to_client']),
//                        'icon' => 'fas fa-user-plus',
//                    ]);

                    array_push($dept_menu, [
                        'title' => 'Ticket Log',
                        'link' => route('frontend.ecs_flight_transactions.ticketLogClientSelection'),
                        'icon' => 'fas fa-rectangle-list',
                    ]);

                    array_push($dept_menu, [
                        'title' => 'Account Summaries',
                        'link' => route('frontend.ecs_client_account_summaries.index'),
                        'icon' => 'fas fa-list-alt',
                    ]);

                    array_push($dept_menu, [
                        'title' => 'Reconciliations',
                        'link' => route('frontend.ecs_reconciliations.index'),
                        'icon' => 'fas fa-trowel-bricks',
                    ]);
                }

                array_push($dept_menu, [
                    'title' => 'Clients',
                    'link' => route('frontend.ecs_clients.index'),
                    'icon' => 'fas fa-list-alt',
                ]);

                $menu_group = [
                    'title' => 'ECS',
                    'link' => '#cbt',
                    'icon' => 'fas fa-file-alt',
                    'group' => 'cbt',
                    'links' => $dept_menu
                ];

                array_push($menus, $menu_group);
            }

            if ($user_is_ecs_client) {
                array_push($menus, [
                    'title' => 'ECS Account Summaries',
                    'link' => route('frontend.ecs_client_portal.accountSummaries'),
                    'icon' => 'fas fa-tachometer-alt',
                    'sidebar_only' => false
                ]);

                array_push($menus, [
                    'title' => 'View Requests',
                    'link' => route('frontend.ecs_client_portal.clientTrxs'),
                    'icon' => 'fas fa-eye',
                ]);

                array_push($menus, [
                    'title' => 'Ticket Log',
                    'link' => route('frontend.ecs_client_portal.ticketLog'),
                    'icon' => 'fas fa-rectangle-list',
                    'sidebar_only' => false
                ]);
                array_push($menus, [
                    'title' => 'ECS Profile',
                    'link' => route('frontend.ecs_client_portal.clientProfile'),
                    'icon' => 'fas fa-list-alt',
                    'sidebar_only' => false
                ]);

            }
            // ECS end


            //            if($user->can('manage fuel records')){
            //                array_push($menus, [
            //                    'title' => 'Fueling Records',
            //                    'link' => route('frontend.fuel_discrepancies.reports.index'),
            //                    'icon' => 'fas fa-list-alt',
            //                    'sidebar_only' => false
            //                ]);
            //            }

            // Fuel Records start
            unset($menu_group);
            unset($dept_menu);
            $dept_menu = array();
            if ($user->can('plan Flights') || (Pilot::where('company_id', $staff_ara_id)->count()) || $user_is_admin) {
                //                array_push($dept_menu, [
                //                    'title' => 'Fuel Records',
                //                    'link' => route('frontend.fuel_discrepancies.techLogDataEntry'),
                //                    'icon' => 'fas fa-list-alt',
                //                ]);

                array_push($dept_menu, [
                    'title' => 'Dashboard',
                    'link' => route('frontend.flight_envelopes.records.index'),
                    'icon' => 'fas fa-list-alt',
                ]);

                $menu_group = [
                    'title' => 'Flight Envelope',
                    'link' => '#fuel-records',
                    'icon' => 'fas fa-gas-pump',
                    'group' => 'fuel_records',
                    'links' => $dept_menu
                ];

                array_push($menus, $menu_group);
            }
            // Fuel Records end

            // Ticket Glitches start
            if ($user->hasRole('call center admin') || $user->hasRole('call center agent') || $user_is_admin) {
                array_push($menus, [
                    'title' => 'Ticket Glitches',
                    'link' => route('frontend.ticket_glitches_report.index'),
                    'icon' => 'fas fa-list-alt',
                ]);
            }
            // Ticket Glitches end

            // Pax Complaints Mgt start
            unset($menu_group);
            unset($dept_menu);
            $dept_menu = array();
            if ($user->hasRole('ambassador') || $user->can('manage pax complaints') || $user_is_admin) {
                array_push($dept_menu, [
                    'title' => 'List',
                    'link' => route('frontend.pax_complaints.index'),
                    'icon' => 'fas fa-list-alt',
                ]);

                array_push($dept_menu, [
                    'title' => 'QR Code',
                    'link' => route('frontend.qr_code'),
                    'icon' => 'fas fa-qrcode',
                ]);

                $menu_group = [
                    'title' => 'Pax Complaints Mgt',
                    'link' => '#pax_complaints',
                    'icon' => 'fas fa-circle-exclamation',
                    'group' => 'ticket_glitches',
                    'links' => $dept_menu
                ];

                array_push($menus, $menu_group);
            }
            // Pax Complaints Mgt end

            // Disruption Logs start
            if ($user->can('manage disruption logs') || $user_is_admin) {
                array_push($menus, [
                    'title' => 'Disruption Logs',
                    'link' => route('frontend.flight_disruption.index'),
                    'icon' => 'fas fa-plane',
                ]);
            }
            // Disruption Logs end

            // Finance RA start
            if ($user->can('enter finance ra logs') || $user_is_admin) {
                array_push($menus, [
                    'title' => 'Finance RA ',
                    'link' => route('frontend.finance.ra.index'),
                    'icon' => 'fas fa-money-bill',
                ]);
            }
            // Finance RA End

            // Fuel Consumption Reports start
            if ($user->can('enter fuel consumption reports') || $user_is_admin) {
                array_push($menus, [
                    'title' => 'Fuel Consumption Reports',
                    'link' => route('frontend.fuel_consumption_reports.index'),
                    'icon' => 'fas fa-gas-pump',
                ]);
            }
            // Fuel Consumption Reports End

            // Flight Ops summaries start
            if ($user->can('enter flight ops summaries') || $user_is_admin) {
                array_push($menus, [
                    'title' => 'Flight Ops Summaries',
                    'link' => route('frontend.flight_ops_summaries.index'),
                    'icon' => 'fas fa-plane',
                ]);
            }
            // Flight Ops summaries End

            // ACFA start
            if ($user->can('manage acfa dash') || $user_is_admin) {
                unset($menu_group);
                unset($dept_menu);
                $dept_menu = array();
                array_push($dept_menu, [
                    'title' => 'ACFA',
                    'link' => route('frontend.airline_fares.index'),
                    'icon' => 'fas fa-plane',
                ]);

                array_push($dept_menu, [
                    'title' => 'ACFA Data',
                    'link' => route('frontend.airline_fares.airrm-reports'),
                    'icon' => 'fas fa-list-alt',
                ]);

                $menu_group = [
                    'title' => 'ACFA',
                    'link' => '#acfa',
                    'icon' => 'fas fa-file-alt',
                    'group' => 'acfa',
                    'links' => $dept_menu
                ];

                array_push($menus, $menu_group);
            }


            // ACFA End
            $serviceNowGroups = ServiceNowGroup::all();
            // ServiceNow Start
            $id = $serviceNowGroups->where('name', 'I.T. ServiceNow')->first()->id;
            if (isset($staff_member) || $user_is_admin && $id) {
                array_push($menus, [
                    'title' => 'IT ServiceNow',
                    'link' => route('frontend.service_now.tickets.index', $id),
                    'icon' => 'fas fa-laptop',
                ]);
            }
            // ServiceNow End

            // Admin services ServiceNow Start
            //            $id = $serviceNowGroups->where('name', 'Admin ServiceNow')->first()->id;
            //            if (isset($staff_member) || $user_is_admin && $id) {
            //                array_push($menus, [
            //                    'title' => 'Admin Services',
            //                    'link' => route('frontend.service_now.tickets.index', $id),
            //                    'icon' => 'fas fa-building',
            //                ]);
            //            }
            // Admin services ServiceNow End

            $is_agent = null;
            if ($staff_ara_id)
                $is_agent = ServiceNowGroupAgent::where('staff_ara_id', $staff_ara_id)->get();

            // Finance services ServiceNow Start
            $id = $serviceNowGroups->where('name', 'Finance ServiceNow')->first()->id;
            if ((isset($staff_member) && ($staff_member->department_name == 'FINANCE' || (isset($is_agent) && $is_agent->where('service_now_group_id', $id)->count()))) || $user_is_admin && $id) {
                array_push($menus, [
                    'title' => 'Finance ServiceNow',
                    'link' => route('frontend.service_now.tickets.index', $id),
                    'icon' => 'fas fa-building',
                ]);
            }
            // Finance services ServiceNow End

            // PSS services ServiceNow Start
            $id = $serviceNowGroups->where('name', 'PSS ServiceNow')->first()->id;
            //            if ((isset($staff_member) && (in_array($staff_member->department_name, ['GROUND OPERATIONS', 'COMMERCIAL', 'OCC']) || (isset($is_agent) && $is_agent->where('service_now_group_id', $id)->count()))) || $user_is_admin && $id) {
            if ($user_is_admin || (isset($staff_member) && in_array($staff_ara_id, ['8113C', '7299']))) {
                array_push($menus, [
                    'title' => 'PSS ServiceNow',
                    'link' => route('frontend.service_now.tickets.index', $id),
                    'icon' => 'fas fa-building',
                ]);
            }
            // PSS services ServiceNow End

            // CBT start
            unset($menu_group);
            unset($dept_menu);
            $dept_menu = array();
            if ($user->can('manage CBT') || $user_is_admin) {
                array_push($dept_menu, [
                    'title' => 'Dashboard',
                    'link' => route('frontend.qr_code'),
                    'icon' => 'fas fa-qrcode',
                ]);

                array_push($dept_menu, [
                    'title' => 'CBT Subjects',
                    'link' => route('cbt_subjects.cbt_subject.index'),
                    'icon' => 'fas fa-list-alt',
                ]);

                array_push($dept_menu, [
                    'title' => 'CBT Exams',
                    'link' => route('cbt_exams.cbt_exam.index'),
                    'icon' => 'fas fa-list-alt',
                ]);

                $menu_group = [
                    'title' => 'CBT',
                    'link' => '#cbt',
                    'icon' => 'fas fa-file-alt',
                    'group' => 'cbt',
                    'links' => $dept_menu
                ];

                array_push($menus, $menu_group);
            }
            // CBT end


            // L and D start
            unset($menu_group);
            unset($dept_menu);
            $dept_menu = array();
            if ($user->can('manage L and D Backend') || $user_is_admin) {
                array_push($dept_menu, [
                    'title' => 'Dashboard',
                    'link' => route('frontend.qr_code'),
                    'icon' => 'fas fa-qrcode',
                ]);

                array_push($dept_menu, [
                    'title' => 'Trainings/Courses',
                    'link' => route('frontend.l_and_d_training_courses.index'),
                    'icon' => 'fas fa-list-alt',
                ]);

                $menu_group = [
                    'title' => 'L and D',
                    'link' => '#cbt',
                    'icon' => 'fas fa-file-alt',
                    'group' => 'cbt',
                    'links' => $dept_menu
                ];

                array_push($menus, $menu_group);
            }
            // L and D end

            if ($user_is_admin) {
                array_push($menus, [
                    'title' => 'CSV Import',
                    'link' => route('csv.uploadForm'),
                    'icon' => 'fas fa-upload-alt',
                    'sidebar_only' => true
                ]);
            }

            if ($user_is_admin || (isset($staff_member) && in_array($staff_member->staff_ara_id, ['6878', '8113C', '8114C']))) {
                array_push($menus, [
                    'title' => 'Service Now Agents',
                    'link' => route('frontend.service_now_group_agents.index'),
                    'icon' => 'fas fa-list',
                    'sidebar_only' => true
                ]);

                array_push($menus, [
                    'title' => 'Service Now Viewers',
                    'link' => route('frontend.service_now_group_viewers.index'),
                    'icon' => 'fas fa-list',
                    'sidebar_only' => true
                ]);
            }


            if ($user->can('manage legal team')) {
                unset($menu_group);
                unset($dept_menu);
                $dept_menu = array();
                array_push($dept_menu, [
                    'title' => 'External Lawyers',
                    'link' => route('frontend.legal_team_external_lawyers.index'),
                    'icon' => 'fas fa-users'
                ]);

                //                array_push($dept_menu, [
                //                    'title' => 'Folders',
                //                    'link' => route('frontend.legal_team_folders.index'),
                //                    'icon' => 'fas fa-folder',
                //                ]);
                //
                //                array_push($dept_menu, [
                //                    'title' => 'Folder Access',
                //                    'link' => route('frontend.legal_team_folder_accesses.index'),
                //                    'icon' => 'fas fa-shield'
                //                ]);

//                array_push($menus, [
//                    'title' => 'Cases',
//                    'link' => route('frontend.legal_team_cases.index'),
//                    'icon' => 'fas fa-file-alt',
//                    'sidebar_only' => false
//                ]);
                $menu_group = [
                    'title' => 'Legal Team',
                    'link' => '#hmo',
                    'icon' => 'fas fa-file-alt',
                    'group' => 'hmo',
                    'links' => $dept_menu
                ];

                array_push($menus, $menu_group);
            }

            if ($user->hasRole('external lawyer')) {
                array_push($menus, [
                    'title' => 'Legal: Cases',
                    'link' => route('frontend.legal_team_cases.index'),
                    'icon' => 'fas fa-file-alt',
                    'sidebar_only' => false
                ]);
            }

            array_push($menus, [
                'title' => 'Logout',
                'link' => route('frontend.auth.logout'),
                'icon' => 'fas fa-sign-out-alt',
                'sidebar_only' => true
            ]);
        } else {
            $menus = [];
        }

        $view->with('menus', $menus);
    }
}
