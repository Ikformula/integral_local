@extends('frontend.layouts.app')

@section('title', 'Holidays' )

@section('content')

        <section class="content" id="app">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card arik-card">
                            <div class="card-header">
                                <h3 class="card-title">Holidays</h3>
        <button class="btn btn-primary float-right" @click="showAddHolidayModal">Add Holiday</button>
                            </div>
                            <div class="card-body p-0">
        <!-- Holiday List -->
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Title</th>
                <th>Entered By</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @verbatim
            <tr v-for="(holiday, index) in holidays" :key="holiday.id">
                <td>{{ holiday.title }}</td>
                <td>{{ holiday.entered_by_staff_ara_id }}</td>
                <td>{{ holiday.holidate }}</td>
                <td>
                    <button class="btn btn-sm btn-info" @click="showEditHolidayModal(holiday)">Edit</button>
                    <button class="btn btn-sm btn-danger" @click="showDeleteConfirmationModal(holiday)">Delete</button>
                </td>
            </tr>
            @endverbatim
            </tbody>
        </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    <!-- Add Holiday Modal -->
    <div class="modal fade" id="addHolidayModal" tabindex="-1" role="dialog" aria-labelledby="addHolidayModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addHolidayModalLabel">Add Holiday</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="addHoliday">
                        @csrf
                        <div class="form-group">
                            <label for="addTitle">Title</label>
                            <input type="text" class="form-control" id="addTitle" v-model="newHoliday.title" required>
                        </div>
                        <div class="form-group">
                            <label for="addEnteredByStaff">Entered By Staff ID</label>
                            <input type="text" class="form-control" id="addEnteredByStaff" v-model="newHoliday.entered_by_staff_ara_id" required>
                        </div>
                        <div class="form-group">
                            <label for="addHolidate">Holiday Date</label>
                            <input type="date" class="form-control" id="addHolidate" v-model="newHoliday.holidate" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Add Holiday</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Holiday Modal -->
    <div class="modal fade" id="editHolidayModal" tabindex="-1" role="dialog" aria-labelledby="editHolidayModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editHolidayModalLabel">Edit Holiday</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="updateHoliday">
                        @csrf
                        <input type="hidden" v-model="editedHoliday.id">
                        <div class="form-group">
                            <label for="editTitle">Title</label>
                            <input type="text" class="form-control" id="editTitle" v-model="editedHoliday.title" required>
                        </div>
                        <div class="form-group">
                            <label for="editEnteredByStaff">Entered By Staff ID</label>
                            <input type="text" class="form-control" id="editEnteredByStaff" v-model="editedHoliday.entered_by_staff_ara_id" required>
                        </div>
                        <div class="form-group">
                            <label for="editHolidate">Holiday Date</label>
                            <input type="date" class="form-control" id="editHolidate" v-model="editedHoliday.holidate" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteHolidayModal" tabindex="-1" role="dialog" aria-labelledby="deleteHolidayModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteHolidayModalLabel">Delete Holiday</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this holiday?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" @click="deleteHoliday">Delete</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Include Vue.js script and create Vue instance -->
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14"></script>
    <script>
        var app = new Vue({
            el: '#app',
            data: {
                holidays: [], // To store the fetched holidays
                newHoliday: {
                    title: '',
                    entered_by_staff_ara_id: '',
                    holidate: '',
                },
                editedHoliday: {}, // To store the holiday being edited
                deletingHoliday: null, // To store the holiday being deleted
            },
            methods: {
                // Fetch holidays from the server
                fetchHolidays() {
                    // Make a fetch request to your API endpoint and update this.holidays
                    fetch('{{ route('frontend.attendance.holidays.index') }}')
                        .then(response => response.json())
                        .then(data => {
                            this.holidays = data;
                        })
                        .catch(error => console.error(error));
                },
                // Show the "Add Holiday" modal
                showAddHolidayModal() {
                    $('#addHolidayModal').modal('show');
                },
                // Show the "Edit Holiday" modal and populate fields with data
                showEditHolidayModal(holiday) {
                    // Set the holiday being edited
                    this.editedHoliday = Object.assign({}, holiday);
                    $('#editHolidayModal').modal('show');
                },
                // Show the "Delete Confirmation" modal
                showDeleteConfirmationModal(holiday) {
                    this.deletingHoliday = holiday;
                    $('#deleteHolidayModal').modal('show');
                },
                // Add a new holiday
                addHoliday() {
                    fetch('{{ route('frontend.attendance.holidays.store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                        body: JSON.stringify(this.newHoliday),
                    })
                        .then(response => response.json())
                        .then(data => {
                            this.holidays.push(data); // Add the new holiday to the list
                            this.newHoliday = { title: '', entered_by_staff_ara_id: '', holidate: '' }; // Clear the form
                            $('#addHolidayModal').modal('hide');
                        })
                        .catch(error => console.error(error));
                },
                // Update an existing holiday
                updateHoliday() {
                    fetch(`{{ config('app.url') }}/attendance/holidays/${this.editedHoliday.id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                        body: JSON.stringify(this.editedHoliday),
                    })
                        .then(response => response.json())
                        .then(data => {
                            // Find the index of the edited holiday in the list and update it
                            const index = this.holidays.findIndex(h => h.id === data.id);
                            console.log(index);
                            console.table(data);
                            if (index !== -1) {
                                console.table(this.holidays[index]);
                                this.holidays[index] = data;
                                console.table(this.holidays[index]);
                            }
                            // this.editedHoliday = null; // Clear the edited holiday data
                            // trial
                            this.fetchHolidays();
                            $('#editHolidayModal').modal('hide');
                        })
                        .catch(error => console.error(error));
                },
                // Delete a holiday
                deleteHoliday() {
                    if (!this.deletingHoliday) {
                        return;
                    }

                    fetch(`{{ config('app.url') }}/attendance/holidays/${this.deletingHoliday.id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                    })
                        .then(response => {
                            if (response.ok) {
                                // Remove the deleted holiday from the list
                                const index = this.holidays.indexOf(this.deletingHoliday);
                                if (index !== -1) {
                                    this.holidays.splice(index, 1);
                                }
                                // trial
                                this.fetchHolidays();
                                this.deletingHoliday = null; // Clear the deleting holiday data
                                $('#deleteHolidayModal').modal('hide');
                            } else {
                                console.error('Delete request failed.');
                            }
                        })
                        .catch(error => console.error(error));
                },
            },
            created() {
                // Fetch holidays when the Vue instance is created
                this.fetchHolidays();
            },
        });

    </script>
@endsection
