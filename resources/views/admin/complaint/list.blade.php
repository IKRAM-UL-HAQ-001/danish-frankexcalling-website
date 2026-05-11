@extends('layouts.main')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-11 mb-xl-0 mx-auto my-5 border w-full bg-white rounded d-flex flex-column">
                <div class="d-flex justify-content-between align-items-center p-3 border-bottom mb-5">
                    <h2 class="mb-0">Complaint</h2>
                    <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#exportModal">Show
                        Report</button>
                </div>
                <div class="flex-grow-1 d-flex flex-column justify-content-center align-items-center col-12">
                    <div class="card-body px-0 pb-2 px-3 col-12">
                        <div class="table-responsive p-0">
                            <table  class="table align-items-center mb-0 table-striped table-hover px-2">
                                <thead>
                                    <tr>
                                        @foreach (['User Name', 'Exchange Name', 'Name', 'Phone', 'Feedback', 'Amount', 'Date and Time', 'Action'] as $header)
                                            <th
                                                class="text-uppercase text-secondary font-weight-bolder text-dark text-center">
                                                {{ $header }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody id="DataTableBody">
                                    @foreach ($Complaints as $Complaint)
                                        <tr>
                                            <td class="encrypted-data">{{ $Complaint->user->name }}</td>
                                            <td class="encrypted-data">{{ $Complaint->exchange->name }}</td>
                                            <td class="encrypted-data">{{ $Complaint->name }}</td>
                                            <td class="encrypted-data">{{ $Complaint->phone->phone_number }}</td>
                                            <td class="encrypted-data">{{ $Complaint->feedback }}</td>
                                            <td class="encrypted-data">{{ $Complaint->amount }}</td>
                                            <td class="">{{ $Complaint->updated_at }}</td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center">
                                                    <form method="POST" action="{{ route('admin.complaint.delete') }}"
                                                        class="me-2">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $Complaint->id }}">
                                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                    </form>
                                                    <button type="button" class="btn btn-info btn-sm edit-button"
                                                        data-id="{{ $Complaint->id }}" data-name="{{ $Complaint->name }}"
                                                        data-phone="{{ $Complaint->phone->phone_number }}"
                                                        data-feedback="{{ $Complaint->feedback }}"
                                                        data-amount="{{ $Complaint->amount }}">Edit</button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{$Complaints->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background:#acc301;">
                    <h5 class="modal-title text-white" id="exportModalLabel">Export Complaints</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="reportForm" action="{{ route('export.complaint') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="editexchange" class="form-label">Exchanges</label>
                            <select class="form-select px-3" id="editexchange" name="exchange_id">
                                <option value="" disabled selected>Select Exchange</option>
                                @foreach ($Exchanges as $exchange)
                                    <option value="{{ $exchange->id }}" class="exchange-option encrypted-data">
                                        {{ $exchange->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="edituser" class="form-label">User</label>
                            <select class="form-select px-3" id="edituser" name="user_id">
                                <option value="" disabled selected>Select User</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="sdate" class="form-label">Start Date:</label>
                            <input type="date" class="form-control border px-3" id="sdate" name="start_date" required
                                value="{{ \Carbon\Carbon::today()->toDateString() }}">
                        </div>
                        <div class="mb-3">
                            <label for="edate" class="form-label">End Date:</label>
                            <input type="date" class="form-control border px-3" id="edate" name="end_date" required
                                value="{{ \Carbon\Carbon::today()->toDateString() }}">
                        </div>
                        <button type="submit" class="btn btn-primary" style="background:#acc301;">
                            Generate File
                        </button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editDemoSendModal" tabindex="-1" aria-labelledby="editDemoSendModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-white" id="editDemoSendModalLabel">Edit Complaint</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editDemoSendForm" method="POST" action="{{ route('admin.data_entry.update') }}">
                        @csrf
                        <input type="hidden" id="demoSendId" name="id">
                        <div class="mb-3">
                            <label for="editName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="editName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="editPhone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="editPhone" name="phone" required readonly>
                        </div>
                        <div class="mb-3">
                            <label for="editFeedback" class="form-label">Feedback</label>
                            <input type="text" class="form-control" id="editFeedback" name="feedback" required>
                        </div>
                        <div class="mb-3">
                            <label for="editAmount" class="form-label">Amount</label>
                            <input type="number" class="form-control" id="editAmount" name="amount" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            // Open Edit Modal and populate fields
            document.querySelectorAll('.edit-button').forEach(button => {
                button.addEventListener('click', function() {
                    const demoSendId = button.getAttribute('data-id');
                    const name = decryptData(button.getAttribute('data-name'));
                    const phone = decryptData(button.getAttribute('data-phone'));
                    const feedback = decryptData(button.getAttribute('data-feedback'));
                    const amount = decryptData(button.getAttribute('data-amount'));

                    // Populate the modal fields
                    document.getElementById('demoSendId').value = demoSendId;
                    document.getElementById('editName').value = name;
                    document.getElementById('editPhone').value = phone;
                    document.getElementById('editFeedback').value = feedback;
                    document.getElementById('editAmount').value = amount;

                    // Show the modal
                    const editDemoSendModal = new bootstrap.Modal(document.getElementById(
                        'editDemoSendModal'));
                    editDemoSendModal.show();
                });
            });

            // Submit edited data with taskname set to demosend
            document.getElementById('editDemoSendForm').addEventListener('submit', function(event) {
                event.preventDefault(); // Prevent default form submission
                const form = document.getElementById('editDemoSendForm');
                const formData = new FormData();
                formData.append('id', form.demoSendId.value);
                formData.append('customer_name', encryptData(form.editName.value));
                // formData.append('customer_phone', encryptData(form.editPhone.value));
                formData.append('customer_feedback', encryptData(form.editFeedback.value));
                formData.append('customer_amount', encryptData(form.editAmount.value));

                // Set the task_name to demosend
                formData.append('task_name', 'complaint');

                // Submit the form data
                fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        form.reset();
                        window.location.reload();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            });
        });
    </script>


    <script>
        document.getElementById('editexchange').addEventListener('change', function() {
            const exchangeId = this.value;
            if (exchangeId) {
                fetch("{{ route('getusers') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content') // CSRF token
                        },
                        body: JSON.stringify({
                            exchange_id: exchangeId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        const userSelect = document.getElementById('edituser');
                        userSelect.innerHTML =
                            '<option class="" value="" disabled selected>Select User</option>'; // Reset user dropdown

                        // Populate the user dropdown with the fetched users
                        data.users.forEach(user => {
                            const userName = decryptData(user.name); // Decrypt the user name
                            const option = document.createElement('option');
                            option.value = user.id;
                            option.textContent = userName; // Decrypted user name
                            userSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error fetching users:', error));
            }
        });
    </script>
@endsection
