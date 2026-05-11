@extends("layouts.main")
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-11 mb-xl-0 mx-auto my-5 border w-full bg-white rounded d-flex flex-column">
            <div class="d-flex justify-content-between align-items-center p-3 border-bottom mb-5">
                <h2 class="mb-0">Walks</h2>
                <div>
                    <button class="btn btn-dark btn-sm" data-bs-toggle="modal" data-bs-target="#addPhoneNumberModal" data-id="">Form </button>
                    {{-- <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#exportModal"> Show Report </button> --}}
                </div>
            </div>
            <div class="flex-grow-1 d-flex flex-column justify-content-center align-items-center col-12">
                <div class="card-body px-0 pb-2 px-3 col-12">
                    <div class="table-responsive p-0">
                        <table  class="table align-items-center mb-0 table-striped table-hover px-2">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary font-weight-bolder text-dark">Name</th>
                                    <th class="text-uppercase text-secondary font-weight-bolder text-dark">Phone Number</th>
                                    <th class="text-uppercase text-secondary font-weight-bolder text-dark">Feedback</th>
                                    <th class="text-uppercase text-secondary font-weight-bolder text-dark">Amount</th>
                                    <th class="text-center text-uppercase text-secondary font-weight-bolder text-dark">Date and Time</th>
                                </tr>
                            </thead>
                            <tbody id="DataTableBody">
                                @foreach ($Walks as $walk)
                                <tr data-user-id="a" data-exchange-id="a">
                                    <td class="encrypted-data">{{$walk->name}}</td>
                                    <td class="encrypted-data">{{$walk->phone->phone_number  ?? ''}}</td>
                                    <td class="encrypted-data">{{$walk->feedback}}</td>
                                    <td class="encrypted-data">{{$walk->amount}}</td>
                                    <td >{{$walk->created_at}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{$Walks->links()}}
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
                <h5 class="modal-title" id="exportModalLabel" style="color:white">Export Walk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
                <form id="reportForm" action="{{ route('export.walk') }}" method="POST">
                    @csrf
                    <input type="hidden" name="user_id" id="id" value="{{session('user_id')}}">
                    <div class="mb-3">
                        <label for="sdate" class="form-label">Start Date:</label>
                        <input type="date" class="form-control border px-3" id="sdate" name="start_date"
                            required value="{{ \Carbon\Carbon::today()->toDateString() }}">
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

<div class="modal fade" id="addPhoneNumberModal" tabindex="-1" aria-labelledby="addPhoneNumberModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center" style="background: #344767;">
                <h5 class="modal-title text-white" id="addPhoneNumberModalLabel">Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" id="error-alert" style="display: none;"></div>
                    <script>
                        setTimeout(function() {
                            document.getElementById("error-alert").style.display = "none";
                        }, 200);
                    </script>
                <form id="addPhoneForm">
                    @csrf <!-- Add CSRF token for Laravel -->
                    <div class="mb-3">
                        <label for="customer_name" class="form-label">Customer Name </label>
                        <input type="text" class="form-control border px-3" id="customer_name" name="customer_name"
                            placeholder="Enter Customer Name" required>
                    </div>
                    <div class="mb-3">
                        <label for="customer_phone" class="form-label">Customer Phone Number</label>
                        <input type="text" class="form-control border px-3 customer_phone encrypted-data" id="customer_phone" name="customer_phone" >
                        <input type="hidden" class="form-control border px-3 customer_phone encrypted-data" id="phone_id"  name="phone_id" >
                    </div>
                    <div class="mb-3">
                        <label for="feedback" class="form-label">Customer Feedback </label>
                        <input type="text" class="form-control border px-3" id="feedback" name="customer_feedback"
                            placeholder="Enter Feedback" required>
                    </div>
                    <div class="mb-3">
                        <label for="followup" class="form-label">Customer Amount </label>
                        <input type="number" class="form-control border px-3" id="followup" name="customer_amount"
                            placeholder="Enter Amount" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="demo_send_btn" class="btn btn-warning" data-action="{{ route('customer_care.data_entry.post') }}">Demo Send</button>
                <button type="button" id="reject_btn" class="btn btn-warning" data-action="{{ route('customer_care.data_entry.post') }}">Reject</button>
                <button type="button" id="rejoin_btn" class="btn btn-primary" data-action="{{ route('customer_care.data_entry.post') }}">Rejoin</button>
                <button type="button" id="refer_id_btn" class="btn btn-warning" data-action="{{ route('customer_care.data_entry.post') }}">Refer ID</button>
                <button type="button" id="new_id_btn" class="btn btn-success" data-action="{{ route('customer_care.data_entry.post') }}">New Id</button>
                <button type="button" id="follow_up_btn" class="btn btn-info" data-action="{{ route('customer_care.data_entry.post') }}">Follow Up</button>
                <button type="button" id="complaint_btn" class="btn btn-dark" data-action="{{ route('customer_care.data_entry.post') }}">Complaint</button>
                <button type="button" id="walk_btn" class="btn btn-primary" data-action="{{ route('customer_care.data_entry.post') }}">Walk</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

  <script>
        const addPhoneNumberModal = document.getElementById('addPhoneNumberModal');

        addPhoneNumberModal.addEventListener('show.bs.modal', (event) => {
            const button = event.relatedTarget;
            const phoneNumber = button.getAttribute('data-phone');

        });

        document.querySelectorAll('.modal-footer button[data-action]').forEach(button => {
            button.addEventListener('click', () => {
                const form = document.getElementById('addPhoneForm');
                const formData = new FormData();

                let phoneNumber = form.customer_phone.value.replace(/\D/g,''); 

                if (phoneNumber.startsWith('91') && phoneNumber.length > 10) {
                    phoneNumber = phoneNumber.substring(2);
                }

                if (phoneNumber.length !== 10) {
                    alert('Invalid phone number. Please enter a valid 10-digit phone number.');
                    return;
                }

                const encryptedPhone = encryptData(phoneNumber);

                formData.append('customer_name', encryptData(form.customer_name.value));
                formData.append('customer_phone', encryptedPhone);
                formData.append('customer_feedback', encryptData(form.feedback.value));
                formData.append('customer_amount', encryptData(form.followup.value));

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
                } else if (buttonId === 'rejoin_btn') {
                    taskName = 'rejoin';
                }

                formData.append('task_name', taskName);
                formData.append('if_walk', 1);

                const actionRoute = button.getAttribute('data-action');

                fetch(actionRoute, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        },
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) {
                            if (response.status === 404) {
                                return response.json().then(errorData => {
                                    throw new Error(errorData
                                    .error); // Use the error message from the server
                                });
                            }
                        }
                        return response.json();
                    })
                    .then(data => {
                        window.location.reload();
                    })
                    .catch(error => {
                        const errorAlert = document.getElementById('error-alert');
                        errorAlert.innerText = error.message;
                        errorAlert.style.display = 'block';
                    });
            });
        });
    </script>
@endsection
