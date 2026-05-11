@extends('layouts.main')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-11 mb-xl-0 mx-auto my-5 border w-full bg-white rounded d-flex flex-column">
                <div class="d-flex justify-content-between align-items-center p-3 border-bottom mb-5">
                    <h2 class="mb-0">Refer ID</h2>
                    <div>
                        <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#exportModal">Show
                            Report</button>
                    </div>
                </div>
                <div class="flex-grow-1 d-flex flex-column justify-content-center align-items-center col-12">
                    <div class="card-body px-0 pb-2 px-3 col-12">
                        <div class="table-responsive p-0">
                            <table  class="table align-items-center mb-0 table-striped table-hover px-2">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary font-weight-bolder text-dark">User Name
                                        </th>
                                        <th class="text-uppercase text-secondary font-weight-bolder text-dark">Exchange Name
                                        </th>
                                        <th class="text-uppercase text-secondary font-weight-bolder text-dark">Name</th>
                                        <th class="text-uppercase text-secondary font-weight-bolder text-dark">Phone</th>
                                        <th class="text-uppercase text-secondary font-weight-bolder text-dark">Feedback</th>
                                        <th class="text-uppercase text-secondary font-weight-bolder text-dark">Amount</th>
                                        <th class="text-uppercase text-secondary font-weight-bolder text-dark">Date and Time
                                        </th>
                                        <th class="text-center text-uppercase text-secondary font-weight-bolder text-dark">
                                            Action</th>
                                    </tr>
                                </thead>
                                <tbody id="DataTableBody">
                                    @foreach ($ReferIds as $referId)
                                        <tr data-user-id="a" data-exchange-id="a">
                                            <td style="width: 45%;" class="encrypted-data">{{ $referId->user->name }}</td>
                                            <td style="width: 45%;" class="encrypted-data">{{ $referId->exchange->name }}
                                            </td>
                                            <td style="width: 45%;" class="encrypted-data">{{ $referId->name }}</td>
                                            <td style="width: 45%;" class="encrypted-data">{{ $referId->phone->phone_number }}</td>
                                            <td style="width: 45%;" class="encrypted-data">{{ $referId->feedback }}</td>
                                            <td style="width: 45%;" class="encrypted-data">{{ $referId->amount }}</td>
                                            <td >{{ $referId->updated_at }}</td>
                                            <td style="width: 10%; text-align: center;">
                                                <div class="d-flex justify-content-center ">
                                                    <form method="POST" action="{{ route('admin.refer_id.delete') }}">
                                                        @csrf
                                                        <input type="hidden" id="deleteIdInput" name="id"
                                                            value="{{ $referId->id }}">
                                                        <button type="submit"
                                                            class="btn btn-danger btn-sm px-4">Delete</button>
                                                    </form>
                                                    <button type="button" class="btn btn-info btn-sm edit-button mx-3"
                                                        data-id="{{ $referId->id }}" data-name="{{ $referId->name }}"
                                                        data-phone="{{ $referId->phone->phone_number }}"
                                                        data-feedback="{{ $referId->feedback }}"
                                                        data-amount="{{ $referId->amount }}">
                                                        Edit
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{$ReferIds->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between align-items-center " style="background:#acc301;">
                    <h5 class="modal-title" id="exportModalLabel" style="color:white">Export Refer Ids</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form id="reportForm" action="{{ route('export.referId') }}" method="POST">
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
                            <input type="date" class="form-control border px-3" id="edate" name="end_date"
                                required value="{{ \Carbon\Carbon::today()->toDateString() }}">
                        </div>
                        <button type="submit" class="btn" style="background:#acc301;" id="generateReportBtn">
                            <span id="btnText">Generate File</span>
                            <span id="btnSpinner" class="spinner-border spinner-border-sm d-none" role="status"
                                aria-hidden="true"></span>
                        </button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                </div>
            </div>
        </div>
    </div>


    <!-- Edit Demo Send Modal -->
    <div class="modal fade" id="editDemoSendModal" tabindex="-1" aria-labelledby="editDemoSendModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-white" id="editDemoSendModalLabel">Edit Demo Send Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editDemoSendForm" method="post" action="{{ route('admin.data_entry.update') }}">
                        @csrf
                        <input type="hidden" id="demoSendId" name="id">
                        <div class="mb-3">
                            <label for="editName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="editName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="editPhone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="editPhone" name="phone" required readonly>
                            <input type="hidden" class="form-control border px-3 customer_phone encrypted-data"
                                id="phone_id" name="phone_id" readonly>

                        </div>
                        <div class="mb-3">
                            <label for="editFeedback" class="form-label">Feedback</label>
                            <input type="text" class="form-control" id="editFeedback" name="feedback" required>
                        </div>
                        <div class="mb-3">
                            <label for="editAmount" class="form-label">Amount</label>
                            <input type="text" class="form-control" id="editAmount" name="amount" required>
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
                formData.append('task_name', 'referid');

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
