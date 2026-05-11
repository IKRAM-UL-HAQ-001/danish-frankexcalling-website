@extends('layouts.main')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-11 mb-xl-0 mx-auto my-5 border w-full bg-white rounded d-flex flex-column">
                <div class="d-flex justify-content-between align-items-center p-3 border-bottom mb-5">
                    <h2 class="mb-0">Assign Phone Numbers</h2>
                </div>

                <div class="flex-grow-1 d-flex flex-column justify-content-center align-items-center col-12">
                    <div class="card-body px-0 pb-2 px-3 col-12">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        <div class="table-responsive p-0">
                            <table  class="table align-items-center mb-0 table-striped table-hover px-2">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary font-weight-bolder text-dark">Phone Number </th>
                                        <th class="text-uppercase text-secondary font-weight-bolder text-dark ps-2">User Name</th>
                                        <th class="text-uppercase text-secondary font-weight-bolder text-dark ps-2">User Status</th>
                                        <th class="text-uppercase text-secondary font-weight-bolder text-dark ps-2">Date and Time</th>
                                        <th class="text-center text-uppercase text-secondary font-weight-bolder text-dark"> Action</th>
                                    </tr>
                                </thead>
                                <tbody id="DataTableBody">
                                    @foreach ($PhoneNumbers as $phoneNumber)
                                        <tr>
                                            <td class="encrypted-data" id="phoneNumber" data-id="{{ $phoneNumber->id }}">{{ $phoneNumber->phone_number }}</td>
                                            <td class="encrypted-data"> {{ $phoneNumber->user->name }} </td>
                                            <td>{{ $phoneNumber->status }}</td>
                                            <td> {{ $phoneNumber->created_at }} </td>
                                            <td style="width: 10%; text-align: center;">
                                                <button class="btn btn-primary btn-sm open-modal-btn" data-bs-toggle="modal"
                                                    data-bs-target="#addPhoneNumberModal"
                                                    data-id="{{ $phoneNumber->id }}"
                                                    data-phone="{{ $phoneNumber->phone_number }}">
                                                    Form
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{$PhoneNumbers->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addPhoneNumberModal" tabindex="-1" aria-labelledby="addPhoneNumberModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between align-items-center" style="background: #344767;">
                    <h5 class="modal-title text-white" id="addPhoneNumberModalLabel">Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addPhoneForm">
                        @csrf <!-- Add CSRF token for Laravel -->
                        <div class="mb-3">
                            <label for="customer_name" class="form-label">Customer Name </label>
                            <input type="text" class="form-control border px-3" id="customer_name" name="customer_name"
                                placeholder="Enter Customer Name" required>
                        </div>
                        <div class="mb-3">
                            <label for="customer_phone" class="form-label">Customer Phone Number</label>
                            <input type="text" class="form-control border px-3 customer_phone encrypted-data" id="customer_phone" name="customer_phone" readonly>
                            <input type="hidden" class="form-control border px-3 customer_phone encrypted-data" id="phone_id"  name="phone_id" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="feedback" class="form-label">Customer Feedback </label>
                            <input type="text" class="form-control border px-3" id="feedback" name="customer_feedback"
                                placeholder="Enter Feedback" required>
                        </div>
                        <div class="mb-3">
                            <label for="followup" class="form-label">Customer Amount </label>
                            <input type="text" class="form-control border px-3" id="followup" name="customer_amount"
                                placeholder="Enter Amount" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="demo_send_btn" class="btn btn-warning" data-action="{{ route('exchange.data_entry.post') }}">Demo Send</button>
                    <button type="button" id="reject_btn" class="btn btn-warning" data-action="{{ route('exchange.data_entry.post') }}">Reject</button>
                    <button type="button" id="refer_id_btn" class="btn btn-warning" data-action="{{ route('exchange.data_entry.post') }}">Refer ID</button>
                    <button type="button" id="new_id_btn" class="btn btn-success" data-action="{{ route('exchange.data_entry.post') }}">New Id</button>
                    <button type="button" id="follow_up_btn" class="btn btn-info" data-action="{{ route('exchange.data_entry.post') }}">Follow Up</button>
                    <button type="button" id="complaint_btn" class="btn btn-dark" data-action="{{ route('exchange.data_entry.post') }}">Complaint</button>
                    <button type="button" id="walk_btn" class="btn btn-primary" data-action="{{ route('exchange.data_entry.post') }}">Walk</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const addPhoneNumberModal = document.getElementById('addPhoneNumberModal');

        addPhoneNumberModal.addEventListener('show.bs.modal', (event) => {
            const button = event.relatedTarget; // Button that triggered the modal
            const phoneNumber = button.getAttribute('data-phone'); // Extract phone number from data attribute

            // Set the phone number input value
            const customerPhoneInput = document.getElementById('customer_phone');
            customerPhoneInput.value = decryptData(phoneNumber);
        });

        document.querySelectorAll('[data-bs-target="#addPhoneNumberModal"]').forEach(button => {
            button.addEventListener('click', function() {
                const entryId = button.getAttribute('data-id');
                document.getElementById('phone_id').value = entryId;
            });
        });


        document.querySelectorAll('.modal-footer button[data-action]').forEach(button => {
            button.addEventListener('click', () => {
                const form = document.getElementById('addPhoneForm');
                const formData = new FormData();

                formData.append('customer_name', encryptData(form.customer_name.value));
            //  formData.append('customer_phone', encryptData(form.customer_phone.value));
                formData.append('customer_feedback', encryptData(form.feedback.value));
                formData.append('customer_amount', encryptData(form.followup.value));
                formData.append('phone_id', form.phone_id.value);
                let taskName = '';
                const buttonId = button.id;

                if (buttonId === 'demo_send_btn') {
                    taskName = 'demosend';
                } else if (buttonId === 'reject_btn') {
                    taskName = 'reject';
                } else if (buttonId === 'refer_id_btn') {
                    taskName = 'referid';
                } else if (buttonId === 'new_id_btn') {
                    taskName = 'newid';
                } else if (buttonId === 'follow_up_btn') {
                    taskName = 'followup';
                } else if (buttonId === 'complaint_btn') {
                    taskName = 'complaint';
                } else if (buttonId === 'walk_btn') {
                    taskName = 'walk';
                }
                formData.append('task_name', taskName);
                const actionRoute = button.getAttribute('data-action');
                fetch(actionRoute, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: formData
                })
                .then(response => response.json())                    
                .then(data => {
                    window.location.reload();
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        });
    </script>
@endsection
