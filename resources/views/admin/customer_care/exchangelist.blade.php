@extends("layouts.main")
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-11 mb-xl-0 mx-auto my-5 border w-full bg-white rounded d-flex flex-column">
            <div class="d-flex justify-content-between align-items-center p-3 border-bottom mb-5">
                <h2 class="mb-0">Exchange List</h2>
                <div>
                    <button type="button" class="btn btn-light text-white bg-dark" data-bs-toggle="modal" data-bs-target="#myModal">Add Customer Care</button>
                </div>
            </div>
            <div class="flex-grow-1 d-flex flex-column justify-content-center align-items-center col-12">
                <div class="card-body px-0 pb-2 px-3 col-12">
                    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif
                    <div class="table-responsive p-0">
                        <table id="DataTable" class="table align-items-center mb-0 table-striped table-hover px-2">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary font-weight-bolder text-dark ps-2">Exchnage Name</th>
                                    <th class="text-uppercase text-secondary font-weight-bolder text-dark">Date and Time</th>
                                    <th class="text-center text-uppercase text-secondary font-weight-bolder text-dark">Action</th>
                                </tr>
                            </thead>
                            <tbody id="DataTableBody">
                                @foreach ($Exchanges as $exchange)
                                <tr>
                                    <td style="width: 45%;" class="encrypted-data">{{ $exchange->name }}</td>
                                    <td style="width: 45%;" class="">{{ $exchange->created_at }}</td>
                                    <td style="width: 10%; text-align: center;">
                                        <form action="{{ route('admin.customer_care.list') }}" method="POST" style="display:inline;">
                                            @csrf
                                            <input type="hidden" value="{{$exchange->id}}" name="id">
                                            <button type="submit" class="btn btn-danger btn-sm">Exchange user list</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title" id="myModalLabel" style="color:white">Add New Customer Care</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-success text-white" id='success' style="display:none;"></div>
                <div class="alert alert-danger text-white" id='error' style="display:none;"></div>
                <form id="form" method="post" action="{{ route('admin.customer_care.formPost') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="exchange" class="form-label">Exchange</label>
                        <select class="form-select px-3" id="exchange" name="exchange">
                            <option value="" disabled selected>Select Exchange</option>
                            @foreach($Exchanges as $exchange)
                            <option value="{{ $exchange->id }}" class="exchange-option encrypted-data">{{ $exchange->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="user_name" class="form-label">User Name</label>
                        <input type="text" class="form-control border px-3" id="user_name" name="user_name" placeholder="Enter Username" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">User Email</label>
                        <input type="text" class="form-control border px-3" id="email" name="email"
                            placeholder="Enter Email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control border px-3" id="password" name="password" placeholder="Enter Password" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add New User Modal -->
<div class="modal fade" id="dashboardModal" tabindex="-1" aria-labelledby="dashboardModalLabel" aria-hidden="true" style="z-index:99999;">
    <div class="modal-dialog" style="max-width: 90%; z-index:99999;">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title" id="dashboardModalLabel" style="color:white">Customer Care dashboard</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        $('#form').on('submit', function(e) {
            e.preventDefault();
            const userName = encryptData($('#user_name').val());
            const userEmail = encryptData($('#email').val());
            const password = encryptData($('#password').val());
            $('#user_name').val(userName);
            $('#email').val(userEmail);
            $('#password').val(password);
            this.submit();
        });
    });
</script>
@endsection
