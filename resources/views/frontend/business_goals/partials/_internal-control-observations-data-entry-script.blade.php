@php
    $departments = \Illuminate\Support\Facades\DB::table('departments')->distinct()->get()->pluck('name');
@endphp
<script>
    let observationOngoingCounter = 1; // Initial counter for ID

    // Function to add a new observation-ongoing department row
    document.getElementById('add-observation-ongoing-department-btn').addEventListener('click', function() {
        observationOngoingCounter++; // Increment the counter to make unique IDs

        let newRow = document.createElement('div');
        newRow.classList.add('row');
        newRow.id = `observation-ongoing-department-${observationOngoingCounter}`;
        newRow.innerHTML = `
        <div class="col-9">
            <div class="form-group">
                <select class="form-control select2" name="observation_ongoings[]" required>
                    <option selected disabled>Select One</option>
                    @foreach($departments as $department)
        <option value="{{ $department }}">{{ $department }}</option>
                @endforeach
        </select>
        <label>Department</label>
    </div>
</div>
<div class="col-2">
    <input type="number" step="1" min="0" class="form-control" name="observation_ongoing_amounts[]" required>
    <label>Number</label>
</div>
<div class="col-1">
    <button type="button" class="btn btn-danger remove-observation-ongoing-department-btn"><i class="fa fa-times"></i></button>
</div>
`;

        // Insert the new row before the add button's parent
        document.getElementById('add-observation-ongoing-department-btn').parentNode.before(newRow);

        // Re-initialize select2 on the newly added select
        $(newRow).find('.select2').select2({
            theme: 'bootstrap4'
        });
    });

    // Event delegation to handle dynamic removal of observation-ongoing department rows
    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('remove-observation-ongoing-department-btn')) {
            e.target.closest('.row').remove();
        }
    });
</script>

<script>
    let observationCompletedCounter = 1; // Initial counter for ID

    // Function to add a new observation-completed department row
    document.getElementById('add-observation-completed-department-btn').addEventListener('click', function() {
        observationCompletedCounter++; // Increment the counter to make unique IDs

        let newRow = document.createElement('div');
        newRow.classList.add('row');
        newRow.id = `observation-completed-department-${observationCompletedCounter}`;
        newRow.innerHTML = `
        <div class="col-9">
            <div class="form-group">
                <select class="form-control select2" name="observation_completeds[]" required>
                    <option selected disabled>Select One</option>
                    @foreach($departments as $department)
        <option value="{{ $department }}">{{ $department }}</option>
                @endforeach
        </select>
        <label>Department</label>
    </div>
</div>
<div class="col-2">
    <input type="number" step="1" min="0" class="form-control" name="observation_completed_amounts[]" required>
    <label>Number</label>
</div>
<div class="col-1">
    <button type="button" class="btn btn-danger remove-observation-completed-department-btn"><i class="fa fa-times"></i></button>
</div>
`;

        // Insert the new row before the add button's parent
        document.getElementById('add-observation-completed-department-btn').parentNode.before(newRow);

        // Re-initialize select2 on the newly added select
        $(newRow).find('.select2').select2({
            theme: 'bootstrap4'
        });
    });

    // Event delegation to handle dynamic removal of observation-completed department rows
    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('remove-observation-completed-department-btn')) {
            e.target.closest('.row').remove();
        }
    });
</script>
