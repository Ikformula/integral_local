@extends('frontend.layouts.standalone')

@section('title', 'BSC PDF')

@push('after-styles')
    @include('frontend.business_goals.partials._groups-styles')
    <style>
        .pdf-page {
            page-break-before: always;
            page-break-inside: avoid;
            break-before: page;
            break-inside: avoid;
        }

    </style>
@endpush


@section('content')
    <div class="container-fluid py-2" id="content-to-print">
        <script>
            let bscStats = @json($bsc_stats);
            let commRevenueStats = '';
        </script>

        <div class="row my-3">
            <div class="col-sm-8">
                <h3>
                    Wk {{ $week_in_focus->week_number }}: {{ $week_in_focus->from_day }} - {{ $week_in_focus->to_day }}
                </h3>
            </div>
{{--            <div class="col-sm-4">--}}
{{--                <button type="button" class="btn bg-navy" id="download-pdf">--}}
{{--                    Download PDF--}}
{{--                </button>--}}
{{--            </div>--}}
        </div>

        <div id="bsc-stats" class="row"></div>
        <div id="commercial-stats" class="row"></div>

        @include('frontend.business_goals.parts._periodic-stats')
{{--        <div class="card-columns">--}}

            @foreach($business_areas_custom_order as $order)
            <div class="row">
                @php
                    $business_area = $all_business_area_array[$order];
                    $business_area_id = $business_area->id;
                    $presentation_data = $multi_presentation_data[$business_area->id];
                    $form_fields = $multi_form_fields[$business_area->id];
                    $form_fields_collection = $multi_form_fields_collection[$business_area->id];
                @endphp

                <div class="col-md-12">
                    <div class="card arik-card pdf-page">
{{--                        <div class="card-header shadow-sm">--}}
{{--                            {{ $business_area->name }}--}}
{{--                        </div>--}}
                        <div class="card-body">
                            <div id="bsc-stats-content-{{ $business_area_id }}">
                                @include('frontend.business_goals.quadrants._'.$business_area_id, ['bsc_stats' => &$bsc_stats, 'presentation_mode' => 'on', 'no_daily' => 1])
                            </div>
                        </div>
                    </div>
                </div>
        </div>
            @endforeach

    </div>
        @endsection

@push('after-scripts')
    <script>
        $(document).ready(function(){

            // Keys for which we want to create widgets
            let keysToDisplay = [
            @if($logged_in_user->can('see all business score cards') || in_array($logged_in_user->email, ['ailemen.arumemi-johnson@arikair.com', 'henry.ejiogu@arikair.com']))
                68, // Available Aircraft
                62, // No. of Flights operated
                66, // Total PAX
                'Total Sales',
                56, // Average Fare
                55, // Revenue
                'OTP', // OTP
                'Load Factor',
                'Completion Factor'

            @else

                68, // Available Aircraft
                62, // No. of Flights operated
                66, // Total PAX
                'OTP', // OTP
                'Load Factor',
                'Completion Factor'
            @endif
                ];

            // Get the container where the widgets will be appended
            let container = document.getElementById('bsc-stats');
            // Generate widgets for each key
            keysToDisplay.forEach(function(key) {
                // Ensure the key exists in the bscStats data
                if (bscStats[key]) {
                    let title = bscStats[key].title;
                    let currentWeek = bscStats[key].weeks['Wk {{ $week_in_focus->week_number }}'];
                    let previousWeek = bscStats[key].weeks['Wk {{ $previousWeek->week_number }}'];
                    let variance = bscStats[key].variance;
                    let targetColourA = bscStats[key].target_colours ? bscStats[key].target_colours.a : '';
                    let targetColourB = bscStats[key].target_colours ? bscStats[key].target_colours.b : '';
                    let unitSymbol = bscStats[key].unit_symbol ?? '';
                    let targetAmount = bscStats[key].target;
                    let targetContent = '';
                    if(targetAmount && typeof targetAmount == "number"){
                        targetContent = `<button class="ml-5 btn bg-navy" style="display:inline;">${targetAmount ? 'Target: ' + targetAmount + unitSymbol: ''}</button>`;
                    }

                    // Create the widget HTML structure
                    let widgetHtml = `
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header border-0" style="">
                            <h5 class="mb-0" style="display:inline;">${title}</h4>
                                    ${targetContent}
                                    ${variance}

                        </div>
                        <div class="card-body pt-0 pb-1">
                            <div class="row">
                                <div class="col-6">
                                    <p class="text-muted mb-0">Wk {{ $week_in_focus->week_number }}</p>
                                    <strong class=" ${targetColourA}">${currentWeek}</strong>
                                </div>
                                <div class="col-6">
                                    <p class="text-muted mb-0">Wk {{ $previousWeek->week_number }}</p>
                                    <strong class=" ${targetColourB}">${previousWeek}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

                    // Append the widget to the container
                    container.innerHTML += widgetHtml;
                }
            });

            document.getElementById('commercial-stats').innerHTML = commRevenueStats;

            // ✅ Signal to wkhtmltopdf that PDF is ready for generation
            window.status = 'pdf-ready';
            console.log('PDF Ready status triggered');
        });


    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        document.getElementById('download-pdf').addEventListener('click', () => {
            const btn = document.getElementById('download-pdf');
            const originalText = btn.innerHTML;

            // Set loading state
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Generating PDF...';

            const element = document.getElementById('content-to-print'); // ✅ Capture only this section
            const opt = {
                margin: 0,
                filename: 'cards.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2, useCORS: true },
                jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' },
                pagebreak: { mode: ['css'] }
            };

            // Use new Promise to ensure loading state works correctly
            html2pdf()
                .set(opt)
                .from(element)
                .toPdf()
                .get('pdf')
                .then(pdf => {
                    pdf.save(opt.filename);
                })
                .then(() => {
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                })
                .catch((err) => {
                    console.error(err);
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                    alert('Failed to generate PDF');
                });
        });
    </script>

@endpush
