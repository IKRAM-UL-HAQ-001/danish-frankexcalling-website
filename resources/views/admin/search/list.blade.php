@extends('layouts.main')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-11 mb-xl-0 mx-auto my-5 border w-full bg-white rounded d-flex flex-column">
                <div class="d-flex justify-content-between align-items-center p-3 border-bottom mb-5">
                    <h2 class="mb-0">Data Entry Search</h2>
                    <div>
                        <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#exportModal"> Show
                            Report </button>
                    </div>
                </div>
                <div class="flex-grow-1 d-flex flex-column justify-content-center align-items-center col-12">
                    <div class="card-body px-0 pb-2 px-3 col-12">
                        <div class="table-responsive p-0">
                            <div class="row mb-3 d-flex justify-content-end">
                                <div class="col-md-4 mr-2">
                                    <form method="POST" id="searchFormDataEntry" class="d-flex"
                                        action="{{ route('admin.searchDataEntry.post') }}">
                                        @csrf
                                        <input type="text" id="tableSearch" name="phone_number"
                                            class="form-control rounded-start py-1 px-2"
                                            placeholder="Search From Data Entry...">
                                        <button id="searchButton" type="submit" class="btn btn-primary px-3 py-3 m-0"
                                            style="background:#acc300; border-radius:0;">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <table  class="table align-items-center mb-0 table-striped table-hover px-2">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary font-weight-bolder text-dark">User Name
                                        </th>
                                        <th class="text-uppercase text-secondary font-weight-bolder text-dark">Exchange</th>
                                        <th class="text-uppercase text-secondary font-weight-bolder text-dark">Customer Name
                                        </th>
                                        <th class="text-uppercase text-secondary font-weight-bolder text-dark">Phone Number
                                        </th>
                                        <th class="text-uppercase text-secondary font-weight-bolder text-dark">Feedback</th>
                                        <th class="text-uppercase text-secondary font-weight-bolder text-dark">Amount</th>
                                        <th class="text-uppercase text-secondary font-weight-bolder text-dark">Task Name
                                        </th>
                                        <th class="text-center text-uppercase text-secondary font-weight-bolder text-dark">
                                            Date and Time</th>
                                        <th class="text-uppercase text-secondary font-weight-bolder text-dark">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="DataTableBody">
                                    @if ($DataSearch == null)
                                        <tr>
                                            <td colspan="9" class="text-center">No Records</td>
                                        </tr>
                                    @else
                                        @foreach ($DataSearch as $followup)
                                            <tr data-user-id="a" data-exchange-id="a">
                                                <td class="encrypted-data">{{ $followup->user->name }}</td>
                                                <td class="encrypted-data">{{ $followup->exchange->name }}</td>
                                                <td class="encrypted-data">{{ $followup->name }}</td>
                                                <td class="encrypted-data">{{ $followup->phone->phone_number }}</td>
                                                <td style="width: 45%;" class="encrypted-data">{{ $followup->feedback }}
                                                </td>
                                                <td style="width: 45%;" class="encrypted-data">{{ $followup->amount }}</td>
                                                <td style="width: 45%;" class="encrypted-data">{{ $followup->task_name }}
                                                </td>
                                                <td style="width: 45%;" class="">{{ $followup->created_at }}</td>
                                                <td style="width: 45%;" class="encrypted-data">
                                                    <div class="d-flex justify-content-center ">
                                                        <form method="POST" action="{{ route('admin.DataEntry.delete') }}">
                                                            @csrf
                                                            <input type="hidden" id="deleteIdInput" name="id"
                                                                value="{{ $followup->id }}">
                                                            <button type="submit"
                                                                class="btn btn-danger btn-sm px-4">Delete</button>
                                                        </form>
                                                    </div>
                                                </td>

                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#searchFormDataEntry').on('submit', function(e) {
                const inputField = $('#tableSearch');
                const phone_number = encryptData(inputField.val());
                console.log('Original input value:', inputField.val());
                console.log('Decrypted phone number:', phone_number);
                inputField.val(phone_number);
            });
        });
        const addPhoneNumberModal = document.getElementById('addPhoneNumberModal');
        addPhoneNumberModal.addEventListener('show.bs.modal', (event) => {
            const button = event.relatedTarget; // Button that triggered the modal
            const phoneNumber = button.getAttribute('data-phone'); // Extract phone number from data attribute

            // Set the phone number input value
            const customerPhoneInput = document.getElementById('customer_phone');
            customerPhoneInput.value = decryptData(phoneNumber);
        });
    </script>
@endsection
