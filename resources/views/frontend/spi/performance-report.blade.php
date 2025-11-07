<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('meta_description', 'Arik Air Integral Web Portals')">
    <meta name="author" content="@yield('meta_author', 'Asuquo Bartholomew Ikechukwu')">
    <title>{{ $year }} Safety Performance</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" /><!-- Theme style -->
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('adminlte3.2/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Theme style -->
    {{-- <link rel="stylesheet" href="{{ asset('adminlte3.2/css/adminlte.min.css') }}">--}}
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <style>
        /* Print-specific styles */
        @media print {
            body {
                /*-webkit-print-color-adjust: exact !important;*/
                print-color-adjust: exact !important;
                background-color: white !important;
                color: black !important;
                margin: 0;
                padding: 0;
            }

            @page {
                size: A4;
                margin: 1cm;
            }

            /* Prevent breaks inside elements */
            table,
            tr,
            td,
            th,
            p,
            div,
            h1,
            h2,
            h3,
            h4,
            h5,
            h6 {
                page-break-inside: avoid;
            }

            /* Force page breaks */
            .page-break {
                page-break-before: always;
            }

            /* Images */
            img {
                max-width: 100% !important;
                page-break-inside: avoid;
            }

            /* Links */
            a {
                text-decoration: none;
                color: black !important;
            }

            /* Hide non-essential elements */
            .no-print {
                display: none !important;
            }

            /* Tables */
            table {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                border-collapse: collapse;
            }

            /* Background colors and borders */
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                border-color: black !important;
            }

            /* Ensure that inline/background colors on table cells are preserved */
            td,
            th,
            table,
            .card-header,
            .page-header,
            h2 {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                background-clip: padding-box !important;
            }

            /* Force any inline style backgrounds to be printed */
            td[style],
            th[style],
            div[style],
            span[style] {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            /* Font sizes */
            body {
                font-size: 12pt;
                line-height: 1.5;
            }

            .table-bordered td,
            .table-bordered th {
                border: 1px solid #2e3032;
            }
        }

        /* Basic element rules */
        *,
        ::after,
        ::before {
            box-sizing: border-box;
        }

        h2 {
            margin-top: 0;
            margin-bottom: .5rem;
            font-weight: 700;
            line-height: 1.2;
            font-size: 2rem;
            page-break-after: avoid;
        }

        strong {
            font-weight: bolder;
        }

        table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            border-collapse: collapse;
            border: 1px solid #495057;
        }

        th {
            text-align: inherit;
            vertical-align: bottom;
            border-bottom: 2px solid #495057;
            border: 1px solid #495057;
            padding: .75rem;
        }

        td {
            vertical-align: top;
            border-top: 1px solid #495057;
            border: 1px solid #495057;
            padding: .75rem;
        }

        tbody tr:hover {
            color: #212529;
            background-color: rgba(0, 0, 0, .075);
        }

        /* Print styles */
        @media print {

            *,
            ::after,
            ::before {
                text-shadow: none !important;
                box-shadow: none !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                border-color: black !important;
            }

            thead {
                display: table-header-group;
            }

            tr {
                page-break-inside: avoid;
            }

            h2 {
                orphans: 3;
                widows: 3;
            }

            table {
                border-collapse: collapse !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            td,
            th {
                background-color: #fff !important;
                border: 1px solid #2e3032 !important;
            }

            table,
            tr,
            td,
            th,
            h2 {
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<body class="hold-transition layout-top-nav">
    <div class="wrapper">

        <!-- Print toolbar (hidden when printing) -->
        <div class="no-print" style="padding:12px; background:#f8f9fa; border-bottom:1px solid #e2e6ea;">
            <div style="max-width:1100px; margin:0 auto; display:flex; gap:12px; align-items:center;">
                <button onclick="window.print();" class="btn btn-primary" style="padding:8px 16px;">Print / Save as PDF</button>
                <div style="color:#333; font-size:14px;">For best results enable "Background graphics" or "Background colors and images" in your browser's print dialog.</div>
            </div>
        </div>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper" style="background-color: #ffffff;">

            <!-- Main content -->
            <div class="content">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-7" style="text-align: center; text-transform: capitalize;">
                            <img src="{{ asset('img/Arik_Air-Logo.wine.png') }}" class="img-fluid w-100">

                            <h1 class="my-5">SAFETY DEPARTMENT</h1>

                            <h1 class="my-5" style="color: #dc3545;">SAFETY PERFORMANCE
                                INDICATORS AND TARGETS
                                {{ $year }}
                            </h1>
                        </div>
                    </div>
                    <!-- /.row -->

                    <div class="row my-3">
                        <div class="col-12" style="text-align: center;">
                            <table class="w-100 table-bordered">
                                <tbody>
                                    <tr>
                                        <td rowspan="3">
                                            <img src="{{ asset(config('view.logo.coloured')) }}" class="img-fluid w-100">
                                        </td>
                                        <td>
                                            <h2>SAFETY DEPARTMENT</h2>
                                        </td>
                                        <td>W3-QS- 717</td>
                                    </tr>
                                    <tr>
                                        <td>
                                        </td>
                                        <td>
                                            Date {{ now()->toDateString() }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>SAFETY PERFORMANCE
                                            INDICATORS AND TARGETS
                                            {{ $year }}
                                        </td>
                                        <td>
                                            Revision: 1
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="container">
                        <div class="row mt-5 mb-3">
                            <div class="col-lg-12">
                                <h2 style="text-align: center;">Authorization</h2>

                                <p>This document is published as a Safety Department controlled document to be used for
                                    monitoring each department’s SPI’s and SPT’s.
                                </p>
                                <p>
                                    Details have been extracted from all department’s safety performance indicators and
                                    safety performance targets previously sent in and hereby approved for use</p>
                            </div>

                        </div>
                    </div>

                    <hr style="border-width: 5px; border-color: #6c757d;" />
                    <strong>Prepared and Reviewed by:</strong>
                    <table>
                        <tbody>
                            <tr>
                                <th>
                                    Name:
                                </th>
                                <td>{{ $data['prepared_by_name'] }}</td>
                            </tr>
                            <tr>
                                <th>
                                    Designation:
                                </th>
                                <td>{{ $data['prepared_by_designation'] }}</td>
                            </tr>
                            <tr>
                                <th>
                                    Sign:
                                </th>
                                <td>
                                    @if(isset($data['prepared_by_sign']))
                                    <img src="{{ $data['prepared_by_sign'] }}" height="60px" width="100px" alt="{{ $data['prepared_by_name'] }}'s signature">
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Date:
                                </th>
                                <td>{{ $data['prepared_by_date'] }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <hr style="border-width: 5px; border-color: #6c757d;" class="mb-5" />

                    <hr style="border-width: 5px; border-color: #6c757d;" class="mt-5" />
                    <strong>Approved by:</strong>
                    <table>
                        <tbody>
                            <tr>
                                <th>
                                    Name:
                                </th>
                                <td>{{ $data['approved_by_name'] }}</td>
                            </tr>
                            <tr>
                                <th>
                                    Designation:
                                </th>
                                <td>{{ $data['approved_by_designation'] }}</td>
                            </tr>
                            <tr>
                                <th>
                                    Sign:
                                </th>
                                <td>
                                    @if(isset($data['approved_by_sign']))
                                    <img src="{{ $data['approved_by_sign'] }}" height="60px" width="100px" alt="{{ $data['approved_by_name'] }}'s signature">
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Date:
                                </th>
                                <td>{{ $data['approved_by_date'] }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <hr style="border-width: 5px; border-color: #6c757d;" />


                    <h4 class="mt-5">1. INTRODUCTION</h4>
                    <p>Safety is Arik Air’s top priority. Our commitment is to continually strive to improve operational
                        safety performance and to minimize contributing risks of an accident to as low as reasonably
                        practicable or possible.</p>
                    <p>Safety performance is an element of a Safety Management System (SMS) and enables
                        continuous monitoring and regular assessment of the safety levels achieved by the organization.
                        Setting and monitoring Safety Performance Indicators (SPIs) and Targets (SPTs) is an essential
                        part of safety management activities.</p>
                    <p>The objective of this document is to provide a set of proposed SPIs to assess safety performance
                        through monitoring and to identify areas for improvement.
                        This document is a living document and it will be updated regularly.</p>

                    <h4 class="mt-5">2. SAFETY PERFORMANCE INDICATOR</h4>
                    <h5>2.1 Annual Safety Implementation Plan</h5>
                    <p>The Safety Department shall recommend and provide guidelines for the annual safety
                        implementation plan to operational departments and units (e.g. Flight Operations,
                        Technical/Maintenance, Dispatch/Operational Control, Ground Operations and Cabin
                        Services) and request that they establish departmental safety goals at the end of each
                        year for the upcoming year to achieve the overall corporate safety goals.</p>
                    <p>The annual safety goals need to consider safety requirements from NCAA and other
                        related regulatory agencies, and to include budget, risk identification, risk assessment,
                        and risk control.</p>


                    <h5>2.2 Setting Safety Goals</h5>
                    <p>The safety performance goals shall be set yearly. Each safety goal shall be set based on
                        the actual result of the previous year’s goal. All safety goals shall be managed periodically
                        and shared with appropriate personnel through SAG and SRB meetings, and any other
                        circular.</p>

                    <h5>2.3 Safety Performance Goal Indicator</h5>
                    <p>There is a formal process to develop and maintain a set of safety performance indicators
                        for trend and target monitoring. Arik Air safety goals are based on the frequency of
                        occurrence reports database.</p>

                    <h5>2.4 Establishment of Criteria</h5>
                    <p>Setting each safety goal will be based on the actual result of the previous year’s goal, and
                        it shall be applied based on regulatory and external organization’s requirements such as
                        NCAA, other Civil Aviation Authorities, aircraft design organizations, aircraft
                        manufacturer, original equipment manufacturer (OEM), etc. to accomplish the desired
                        operational safety outcomes.</p>

                    <h5>2.5 Safety Performance Objectives, Targets & Indicators</h5>
                    <p>All safety and security indicators and targets are updated and revised overtime at the
                        direction of the Safety Department.</p>
                    <p>
                        All safety metrics, indicators, and targets must be reviewed at least annually at the Safety
                        Review Board (SRB).</p>


                    @php
                    $colors = ['green' => 'success', 'yellow' => 'warning', 'red' => 'danger'];
                    $colour_codes = ['green' => '#03C03C', 'yellow' => '#FFD700', 'red' => '#FF7F7F'];
                    $sector_header_colours = [
                    '#FFD700',
                    '#800020',
                    '#dc451b',
                    '#6d723c',
                    '#004953',
                    '#8c3d59',
                    '#0767bd',
                    '#64a7d4',
                    '#4664b7',
                    '#EF9B0F',
                    ];

                    // shuffle($sector_header_colours);
                    @endphp

                    @foreach($sectors as $sector)
                    <div class="row mt-5">
                        <div class="col">
                            <table>
                                <thead>
                                    <tr>
                                        <th colspan="7">
                                            <h2 style="color: {{ $sector_header_colours[$loop->index] }}">{{ $sector->sector_name }}</h2>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>OBJECTIVES</th>
                                        <th>INDICATORS</th>
                                        <th>METRICS</th>
                                        @for($i = 1; $i <= 4; $i++)
                                            <th>Q{{ $i }}</th>
                                            @endfor
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(1 < 0)
                                        @foreach($sector->objectives as $objective)
                                        @php
                                        $firstIndicator = true;
                                        @endphp
                                        @foreach($objective->indicators as $indicator)
                                        <tr>
                                            <!-- Objectives column -->
                                            @if($firstIndicator)
                                            <td rowspan="{{ $metrics_count[$objective->id] }}"
                                                style="{{ empty($objective->objectives) ? 'background-color: #f8d7da;' : '' }}">
                                                <strong>{{ $objective->objectives }}</strong>
                                            </td>
                                            @php $firstIndicator = false; @endphp
                                            @endif

                                            <!-- Indicators column -->
                                            <td style="{{ empty($indicator->indicator) ? 'background-color: #f8d7da;' : '' }}">
                                                {{ $indicator->indicator }}
                                            </td>

                                            <td>
                                                <ul class="list-unstyled mb-0">
                                                    @forelse($indicator->metrics as $metric)
                                                    <li style="{{ empty($metric->metric) ? 'background-color: #f8d7da;' : '' }}">
                                                        <strong>{{ $metric->metric }}</strong>
                                                        {{-- <em>({{ $metric->unit }})</em>--}}
                                                    </li>
                                                    @empty
                                                    <li style="background-color: #f8d7da;">No metrics available</li>
                                                    @endforelse
                                                </ul>
                                            </td>
                                        </tr>
                                        @endforeach
                                        @endforeach
                                        @endif


                                        @foreach($sector->objectives as $objective)
                                        @php
                                        $objectiveMetricsCount = $objective->indicators
                                        ->flatMap(fn($indicator) => $indicator->metrics)
                                        ->count();
                                        $firstIndicator = true;
                                        @endphp

                                        @foreach($objective->indicators as $indicator)
                                        @php
                                        $indicatorMetricsCount = $indicator->metrics->count();
                                        @endphp

                                        @foreach($indicator->metrics as $metric)
                                        <tr>
                                            <!-- Objectives column -->
                                            @if($firstIndicator)
                                            <td rowspan="{{ $objectiveMetricsCount }}"
                                                style="{{ empty($objective->objectives) ? 'background-color: #f8d7da;' : '' }}">
                                                <strong>{{ $objective->objectives }}</strong>
                                            </td>
                                            @php $firstIndicator = false; @endphp
                                            @endif

                                            <!-- Indicators column -->
                                            @if($loop->first) <!-- Span the indicator row on first metric row -->
                                            <td rowspan="{{ $indicatorMetricsCount }}"
                                                style="{{ empty($indicator->indicator) ? 'background-color: #f8d7da;' : '' }}">
                                                {{ $indicator->indicator }}
                                            </td>
                                            @endif

                                            <!-- Metrics column -->
                                            <td style="{{ empty($metric->metric) ? 'background-color: #f8d7da;' : '' }}">
                                                <strong>{{ $metric->metric }}</strong>
                                                {{-- <em>({{ $metric->unit }})</em>--}}
                                            </td>

                                            <!-- Quarterly Performance columns -->
                                            @php
                                            $quarterlyPerformances = $performances->where('spi_metric_id', $metric->id); // Relationship data
                                            @endphp

                                            @foreach(['Q1', 'Q2', 'Q3', 'Q4'] as $quarter)
                                            @php
                                            $performance = $quarterlyPerformances->firstWhere('quarter_number', str_replace('Q', '', $quarter));
                                            @endphp
                                            <td style="background-color: {{ empty($performance) || is_null($performance->colour_flag) ? '#f8d7da;' : $colour_codes[$performance->colour_flag] }}">
                                                @if($performance)
                                                {{ $performance->amount }}
                                                @else
                                                N/A
                                                @endif
                                            </td>
                                            @endforeach
                                        </tr>
                                        @endforeach
                                        @endforeach
                                        @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endforeach

                    <h4 class="mt-5">3. APPLICATION AND IMPLEMENTATION</h4>
                    <p>Each operation's department and divisions (Flight Operations, Technical Operations,
                        Operational Control, Ground Operations, Training) shall analyse the results of actual
                        achievements, and send them to the Safety Manager for evaluation of their safety
                        performance.</p>
                    <h5>3.1 Monitoring and Review of Effectiveness</h5>
                    <p>The safety performance goals, safety performance indicators, and the Safety
                        Management System (SMS) are periodically reviewed and monitored proactively and
                        reactively during safety committees and operations meetings to ensure its continuing
                        suitability, adequacy, and effectiveness.</p>
                    <p>Each operations department and division shall carry out a comprehensive causal factor
                        analysis and establish corrective actions when the safety goal is not achieved, and the
                        countermeasures will be established for implementation during safety committee
                        meetings/operations meetings.</p>

                    <h4 class="mt-5">4. CORRECTIVE ACTION PLAN</h4>
                    {!! $corrective_action_plan !!}
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="{{ asset('adminlte3.2/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap -->
    <script src="{{ asset('adminlte3.2/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- overlayScrollbars -->
    <script src="{{ asset('adminlte3.2/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('adminlte3.2/js/adminlte.js') }}"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const startTime = Date.now();

            window.addEventListener("beforeunload", function() {
                const duration = Math.round((Date.now() - startTime) / 1000); // Time in seconds

                fetch("{{ route('user.activity.duration') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        duration: duration,
                        url: window.location.href
                    })
                });
            });
        });
    </script>
</body>

</html>
