@extends('layouts.main')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-11 mb-xl-0 mx-auto my-5 border w-full bg-white rounded d-flex flex-column">
                <div class="d-flex justify-content-between align-items-center p-3 border-bottom mb-5">
                    <h2 class="mb-0">New Id</h2>
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
                                        <!-- <th class="text-uppercase text-secondary font-weight-bolder text-dark">Phone</th> -->
                                        <th class="text-uppercase text-secondary font-weight-bolder text-dark">Feedback</th>
                                        <th class="text-uppercase text-secondary font-weight-bolder text-dark">Amount</th>
                                        <th class="text-uppercase text-secondary font-weight-bolder text-dark">Date and Time
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="DataTableBody">
                                    @foreach ($NewIds as $NewId)
                                        <tr data-user-id="a" data-exchange-id="a">
                                            <td class="encrypted-data">{{ $NewId->user->name }}</td>
                                            <td class="encrypted-data">{{ $NewId->exchange->name }}</td>
                                            <td class="encrypted-data">{{ $NewId->name }}</td>
                                            <!-- <td class="encrypted-data">{{ $NewId->phone->phone_number }}</td> -->
                                            <td class="encrypted-data">{{ $NewId->feedback }}</td>
                                            <td class="encrypted-data">{{ $NewId->amount }}</td>
                                            <td>{{ $NewId->created_at }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{$NewIds->links()}}
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
                <form id="reportForm" action="{{ route('export.complaint')}}" method="POST">
                    @csrf
                    <div class="mb-3">
    <label for="editexchange" class="form-label">Exchanges</label>
    <select class="form-select px-3" id="editexchange" name="exchange_id">
        <option value="" disabled selected>Select Exchange</option>
        @foreach ($Exchanges as $exchange)
            <option value="{{ $exchange->id }}" class="exchange-option encrypted-data">{{ $exchange->name }}</option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label for="edituser" class="form-label">User</label>
    <select class="form-select px-3" id="edituser" name="user_id">
        <option value="" disabled selected>Select User</option>
    </select>
</div>

<script>
   document.getElementById('editexchange').addEventListener('change', function() {
        const exchangeId = this.value;
        if (exchangeId) {
            fetch("{{route('getusers')}}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // CSRF token
                },
                body: JSON.stringify({ exchange_id: exchangeId })
            })
            .then(response => response.json())
            .then(data => {
                const userSelect = document.getElementById('edituser');
                userSelect.innerHTML = '<option class="" value="" disabled selected>Select User</option>'; // Reset user dropdown

                // Populate the user dropdown with the fetched users
                data.users.forEach(user => {
                const userName = decryptData(user.name);  // Decrypt the user name
                const option = document.createElement('option');
                option.value = user.id;
                option.textContent = userName;  // Decrypted user name
                userSelect.appendChild(option);
            });
            })
            .catch(error => console.error('Error fetching users:', error));
        }
    });
</script>
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
@endsection
