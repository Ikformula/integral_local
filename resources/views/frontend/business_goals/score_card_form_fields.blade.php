@extends('frontend.layouts.app')

{{--@push('after-styles')--}}
{{--    <link rel="stylesheet" href="{{ asset('adminlte3.2/plugins/select2/css/select2.min.css') }}">--}}
{{--    <link rel="stylesheet"--}}
{{--          href="{{ asset('adminlte3.2/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">--}}
{{--@endpush--}}

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table" id="formFieldsTable">
                            <thead>
                            <tr>
                                <th class="col-2">Business Area</th>
                                <th>Label</th>
                                <th>Placeholder</th>
                                <th>Unit</th>
                                <th>Type</th>
{{--                                <th>Options</th>--}}
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody style="height: 360px; overflow-y: auto;">
                            @foreach($formFields as $formField)
                                <tr id="formField_{{ $formField->id }}">
                                    <form id="updateForm{{ $formField->id }}" action="{{ route('frontend.business_goals.form_fields.update', $formField->id) }}" method="POST" onsubmit="event.preventDefault(); updateFormField({{ $formField->id }})">
                                        @csrf
                                        @method('PUT')
                                        <td>{{ $formField->businessArea->name }}</td>
                                        <td><input type="text" class="form-control" name="label" value="{{ $formField->label }}"></td>
                                        <td><input type="text" class="form-control" name="placeholder" value="{{ $formField->placeholder }}"></td>
                                        <td><input type="text" class="form-control" name="unit" value="{{ $formField->unit }}"></td>
                                        <td>
                                            <select class="form-control" name="form_type">
                                                <option value="number" {{ $formField->form_type == 'number' ? 'selected' : '' }}>
                                                    Number
                                                </option>
                                                <option value="text" {{ $formField->form_type == 'text' ? 'selected' : '' }}>
                                                    Text
                                                </option>
                                            </select>
                                        </td>
                                        <td>
                                            <button type="submit" class="btn btn-success btn-sm">Update</button>
                                        </td>
                                    </form>
                                    <td>
                                        <form action="{{ route('frontend.business_goals.form_fields.destroy', $formField) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this field?')">
                                            @csrf
                                            @method('DELETE')

                                        <button type="submit" class="btn btn-danger btn-sm">Delete
                                        </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <table class="table">
                            <tbody>
                        <tr>
                            <td colspan="6">Add new field</td>
                        </tr>
                        <tr id="newformField_tr">
                            <form id="addFormFieldForm" action="{{ route('frontend.business_goals.form_fields.store') }}" method="POST" onsubmit="event.preventDefault(); submitNewField();">
                                @csrf
                                <td>
                                    <select class="form-control" name="business_area_id" id="business_area_id_new">
                                        @foreach($business_areas as $business_area)
                                            <option value="{{$business_area->id}}">{{ $business_area->name }}</option>
                                        @endforeach
                                    </select>
                                    <p>Business Area</p>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="label">
                                    <p>Label</p>
                                </td>
                                <td><input type="text" class="form-control" name="placeholder">
                                    <p>Placeholder</p>
                                </td>
                                <td><input type="text" class="form-control" name="unit">
                                    <p>Unit</p>
                                </td>
                                <td>
                                    <select class="form-control" name="form_type">
                                        <option value="number">
                                            Number
                                        </option>
                                        <option value="text">
                                            Text
                                        </option>
                                    </select>
                                    <p>Type</p>
                                </td>
                                <td>
                                    <button type="submit" class="btn btn-success btn-sm">Submit</button>
                                </td>
                            </form>
                        </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Options Modal -->
    <div class="modal fade" id="optionsModal" tabindex="-1" role="dialog" aria-labelledby="optionsModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="optionsModalLabel">Form Field Options</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="addOptionBtn">Add Option</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('after-scripts')
{{--    <script src="{{ asset('adminlte3.2/plugins/select2/js/select2.full.min.js') }}"></script>--}}
{{--    <script>--}}
{{--        $('select').select2({--}}
{{--            theme: 'bootstrap4'--}}
{{--        });--}}
{{--    </script>--}}

    <script>
        function showOptionsModal(formFieldId) {
            // Fetch options for the given form field id and populate the modal
            $('#optionsModal').modal('show');
        }

        function submitNewField() {
                const addFormFieldForm = document.getElementById('addFormFieldForm');
                // addFormFieldForm.addEventListener('submit', function (event) {
                //     event.preventDefault();
                    const formData = new FormData(addFormFieldForm);
                    fetch(addFormFieldForm.action, {
                        method: addFormFieldForm.method,
                        body: formData
                    })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {

                            const newRow = `
                        <tr id="formField_${data.id}">
                                    <form id="updateForm${data.id}" action="{{ url('business_goals/form_fields/') }}/${data.id}" method="POST" onsubmit="event.preventDefault(); updateFormField(${data.id})">
                                        @csrf
                            @method('PUT')
                            <td>${data.business_area}</td>
                        <td><input type="text" class="form-control" name="label" value="${data.label}"></td>
                                        <td><input type="text" class="form-control" name="placeholder" value="${data.placeholder ?? ''}"></td>
                                        <td><input type="text" class="form-control" name="unit" value="${data.unit ?? ''}"></td>
                                        <td>
                                            <select class="form-control" name="form_type">
                                                <option value="number" ${data.form_type == 'number' ? 'selected' : ''}>
                                                    Number
                                                </option>
                                                <option value="text" ${data.form_type == 'text' ? 'selected' : ''}>
                                                    Text
                                                </option>
                                            </select>
                                        </td>
                                        <td>
                                            <button type="submit" class="btn btn-success btn-sm">Update</button>
                                        </td>
                                    </form>
                                    <td>
                                        <form action="{{ url('business_goals/form_fields/') }}${data.id}" method="POST" onsubmit="return confirm('Are you sure you want to delete this field?')">
                                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-danger btn-sm">Delete
                            </button>
                            </form>
                        </td>
                    </tr>
`;
                            document.querySelector('tbody').insertAdjacentHTML('beforeend', newRow);
                            // $('#addFieldModal').modal('hide'); // Close modal
                            showInstantToast('Form field added successfully', 'green');
                            addFormFieldForm.reset();
                            const $select = document.querySelector('#business_area_id_new');
                            $select.value = data.business_area_id;
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showInstantToast('Error occurred while adding form field', 'red');
                        });
                }

        function updateFormField(formFieldId) {
            const form = document.getElementById('updateForm' + formFieldId);
            const formData = new FormData(form);

            fetch(form.action, {
                method: form.method,
                body: formData
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text();
                })
                .then(data => {
                    // Assuming the server response contains a message and color
                    const { message, colour } = JSON.parse(data);
                    showInstantToast(message, colour);
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Handle errors
                });
        }

        function deleteFormField(formFieldId) {
            fetch('/score-card-form-fields/' + formFieldId, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Handle success
                        $('#formField_' + formFieldId).remove();
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        {{--$('#addFormFieldBtn').click(function () {--}}
        {{--    var formData = $('#addFieldModal form').serializeArray();--}}
        {{--    fetch('/score-card-form-fields', {--}}
        {{--        method: 'POST',--}}
        {{--        body: JSON.stringify(formData),--}}
        {{--        headers: {--}}
        {{--            'Content-Type': 'application/json',--}}
        {{--            'X-CSRF-TOKEN': '{{ csrf_token() }}'--}}
        {{--        }--}}
        {{--    })--}}
        {{--        .then(response => response.json())--}}
        {{--        .then(data => {--}}
        {{--            if (data.success) {--}}
        {{--                // Handle success--}}
        {{--            }--}}
        {{--        })--}}
        {{--        .catch(error => console.error('Error:', error));--}}
        {{--});--}}

        // document.getElementById("addFormField").addEventListener("submit", storeFormField);
    </script>
@endpush
