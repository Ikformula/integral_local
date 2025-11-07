@extends('frontend.layouts.app')

@section('title', 'SPI Report Entry')


@section('content')
<div class="container-fluid mt-4">
    <form method="get" id="date-select-form">
        <div class="row">
            <div class="col">
                <div class="row">
                    <div class="col-9">
                        <div class="form-group">
                            <input type="date" name="for_date" id="for_date_input" value="{{ substr($for_date->toDateString(), 0, 10) }}" class="form-control">
                        </div>
                    </div>
                    <div class="col-3">
                        <button type="submit" class="bg-navy btn btn-block">Set Date of Report</button>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                @if(isset($availableDates) && $availableDates->count())
                <div class="form-group">
                    <select class="form-control" id="for_date_dropdown">
                        <option value="">Select date</option>
                        @foreach($availableDates as $date)
                        <option value="{{ $date }}" {{ $date == $for_date->toDateString() ? 'selected' : '' }}>{{ $date }}</option>
                        @endforeach
                    </select>
                    <label class="small text-muted" id="for_date_dropdown">Recent Report Dates</label>
                </div>
                @endif
            </div>
        </div>
    </form>
    @php
    $formattedForDate = $for_date->format('Y-m-d'); // Format for comparison
    @endphp
    <div class="row">
        <div class="col">
            @foreach($sectors as $sector)
            <div class="card mb-4">
                <div class="card-header">
                    <h2>{{ $sector->sector_name }}</h2>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover table-stripped">
                        <thead class="thead-light">
                            <tr>
                                <th>OBJECTIVES</th>
                                <th>INDICATORS</th>
                                <th>METRICS ENTRIES</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sector->objectives as $objective)
                            @php
                            $firstIndicator = true;
                            @endphp
                            @foreach($objective->indicators as $indicator)
                            <tr>
                                <!-- Objectives column -->
                                @if($firstIndicator)
                                <td rowspan="{{ $objective->indicators->count() }}"
                                    style="{{ empty($objective->objectives) ? 'background-color: #f8d7da;' : '' }}">
                                    <strong>{{ $objective->objectives }}</strong>
                                </td>
                                @php $firstIndicator = false; @endphp
                                @endif

                                <!-- Indicators column -->
                                <td style="{{ empty($indicator->indicator) ? 'background-color: #f8d7da;' : '' }}">
                                    {{ $indicator->indicator }}
                                </td>

                                <!-- Metrics column with each metric and unit listed -->
                                <td>
                                    <ul class="list-unstyled mb-0">
                                        @forelse($indicator->metrics->where('id', '!=', 82) as $metric)
                                        <li style="{{ empty($metric->metric) ? 'background-color: #f8d7da;' : '' }}">
                                            <strong>{{ $metric->metric }}</strong> <em>({{ $metric->unit }})</em>
                                            @php
                                            // Find the entry for the given date
                                            $latestEntry = $metric->entries->where('for_date', $formattedForDate)->first();
                                            $entryData = $latestEntry ? json_decode($latestEntry->entry_data, true) : [];
                                            $formula = $metric->metric_formula ? json_decode($metric->metric_formula, true) : null;
                                            $parameters = $formula['parameters'] ?? [['letter' => 'a', 'title' => $metric->metric]];
                                            @endphp

                                            <!-- Metric Entry Form Section -->
                                            <div class="card mt-3">
                                                <div class="card-header">
                                                    <h5>Submit Entry for {{ $metric->metric }}.</h5>
                                                    @if(isset($formula['operation']) && $formula['operation'] != 'a')<p>Formula: {{ $formula['operation'] }}</p>@endif
                                                </div>
                                                <div class="card-body">
                                                    <form id="entry-form-{{ $metric->id }}" onsubmit="return false;">
                                                        <input type="hidden" name="metric_id" value="{{ $metric->id }}">
                                                        <input type="hidden" name="for_date" value="{{ $formattedForDate }}">

                                                        <!-- Parameter Fields -->
                                                        @foreach($parameters as $index => $parameter)
                                                        <div class="form-row mb-2 parameter-row">
                                                            <div class="col-12">
                                                                <label>{{ $parameter['title'] }} ({{ $parameter['letter'] }})</label>
                                                                <input type="number"
                                                                    step="0.001"
                                                                    class="form-control"
                                                                    name="parameters[{{ $parameter['letter'] }}]"
                                                                    value="{{ $entryData[$parameter['letter']] ?? '' }}"
                                                                    placeholder="Enter value for {{ $parameter['title'] }}">
                                                            </div>
                                                        </div>
                                                        @endforeach

                                                        <button type="submit" class="btn bg-navy btn-sm btn-block" id="entry-btn-{{ $metric->id }}" onclick="submitEntry({{ $metric->id }})">Save Entry</button>

                                                        @if($latestEntry)
                                                        <button type="button" class="btn btn-outline-danger btn-sm btn-block mt-2" id="clear-btn-{{ $metric->id }}" onclick="clearEntry({{ $metric->id }}, {{ $latestEntry->id }})">Clear Entry</button>
                                                        @endif

                                                        @if(isset($formula['operation']) && $formula['operation'] != 'a')
                                                        <div class="form-group mt-2 mb-0">
                                                            <input type="number"
                                                                step="0.001"
                                                                class="form-control"
                                                                name="amount" id="metric-{{ $metric->id }}-amount"
                                                                aria-describedby="helpId"
                                                                value="{{ $latestEntry->amount ?? '' }}"
                                                                readonly>
                                                            <small id="helpId"
                                                                class="form-text text-muted">Computed Value @if($metric->unit == 'percentage')(%)@endif</small>
                                                        </div>
                                                        @endif
                                                    </form>
                                                </div>
                                            </div>
                                        </li>
                                        @empty
                                        <li style="background-color: #f8d7da;">No metrics available</li>
                                        @endforelse

                                    </ul>
                                </td>
                            </tr>
                            @endforeach
                            @endforeach


                        </tbody>
                    </table>

                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('after-scripts')
<script>
    // Submit entry form (existing function)
    function submitEntry(metricId) {
        const form = document.getElementById(`entry-form-${metricId}`);
        const formData = new FormData(form);
        const button = document.getElementById(`entry-btn-${metricId}`);

        button.innerHTML = 'Submitting...';
        fetch('{{ route("frontend.safety_review.metric.report.entry.store") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showInstantToast('Entry saved successfully!');
                    $(`#metric-${metricId}-amount`).val(data.amount);
                    // Ensure the Clear Entry button exists and is wired to the saved entry id
                    try {
                        const entryId = data.entry && data.entry.id ? data.entry.id : null;
                        if (entryId) {
                            let clearBtn = document.getElementById(`clear-btn-${metricId}`);
                            if (!clearBtn) {
                                // create the clear button and insert after the save button
                                clearBtn = document.createElement('button');
                                clearBtn.type = 'button';
                                clearBtn.id = `clear-btn-${metricId}`;
                                clearBtn.className = 'btn btn-outline-danger btn-sm btn-block mt-2';
                                clearBtn.innerText = 'Clear Entry';
                                clearBtn.addEventListener('click', function() {
                                    clearEntry(metricId, entryId);
                                });
                                const saveBtn = document.getElementById(`entry-btn-${metricId}`);
                                if (saveBtn && saveBtn.parentNode) {
                                    saveBtn.parentNode.insertBefore(clearBtn, saveBtn.nextSibling);
                                }
                            } else {
                                // update onclick to use the new entry id and show
                                clearBtn.style.display = '';
                                clearBtn.replaceWith(clearBtn.cloneNode(true));
                                clearBtn = document.getElementById(`clear-btn-${metricId}`);
                                clearBtn.addEventListener('click', function() {
                                    clearEntry(metricId, entryId);
                                });
                            }
                        }
                    } catch (e) {
                        console.error('Error creating/showing clear button', e);
                    }
                } else {
                    showInstantToast(`Error: ${data.message}`, 'warning');
                }

                button.innerHTML = 'Save Entry';
            })
            .catch(error => {
                console.error('Error:', error);
                showInstantToast('An error occurred while saving the entry.', 'danger');
                button.innerHTML = 'Save Entry';
            });
    }

    // Clear an existing metric entry
    function clearEntry(metricId, entryId) {
        if (!confirm('Are you sure you want to clear this entry? This will also update quarterly performance.')) return;

        const button = document.getElementById(`clear-btn-${metricId}`);
        button.innerHTML = 'Clearing...';

        fetch('{{ route("frontend.safety_review.metric.report.entry.delete") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    entry_id: entryId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showInstantToast('Entry cleared successfully');
                    // Remove values from the form inputs
                    const form = document.getElementById(`entry-form-${metricId}`);
                    form.querySelectorAll('input[name^="parameters"]').forEach(i => i.value = '');
                    const amountInput = document.getElementById(`metric-${metricId}-amount`);
                    if (amountInput) amountInput.value = '';
                    // hide clear button
                    button.style.display = 'none';
                } else {
                    showInstantToast('Error clearing entry: ' + (data.message || 'Unknown'), 'warning');
                    button.innerHTML = 'Clear Entry';
                }
            })
            .catch(err => {
                console.error(err);
                showInstantToast('An error occurred while clearing the entry.', 'danger');
                button.innerHTML = 'Clear Entry';
            });
    }

    // When the recent-dates dropdown changes, navigate to that date by submitting the top-level form
    document.addEventListener('DOMContentLoaded', function() {
        const dropdown = document.getElementById('for_date_dropdown');
        const selectSmall = document.getElementById('for_date_select');
        const dateInput = document.getElementById('for_date_input');
        const topForm = document.getElementById('date-select-form');

        if (dropdown) {
            dropdown.addEventListener('change', function(e) {
                if (!e.target.value) return;
                // set the hidden/desktop date input value to ensure consistent formatting
                if (dateInput) dateInput.value = e.target.value;
                topForm.submit();
            });
        }

        if (selectSmall) {
            selectSmall.addEventListener('change', function(e) {
                if (!e.target.value) return;
                if (dateInput) dateInput.value = e.target.value;
                topForm.submit();
            });
        }
    });
</script>

@endpush