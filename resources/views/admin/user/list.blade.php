@extends('layouts.main')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-11 mb-xl-0 mx-auto my-5 border w-full bg-white rounded d-flex flex-column">
                <div class="d-flex justify-content-between align-items-center p-3 border-bottom mb-5">
                    <h2 class="mb-0">Users</h2>
                    <div>
                        <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#myModal">Add
                            User</button>
                    </div>
                </div>

                <div class="flex-grow-1 d-flex flex-column justify-content-center align-items-center col-12">
                    <div class="card-body px-0 pb-2 px-3 col-12">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        <div class="table-responsive p-0">
                            <table id="DataTable" class="table align-items-center mb-0 table-striped table-hover px-2">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary font-weight-bolder text-dark ps-2">User
                                            Id</th>
                                        <th class="text-uppercase text-secondary font-weight-bolder text-dark ps-2">User
                                            Name</th>
                                        <th class="text-uppercase text-secondary font-weight-bolder text-dark">Exchange Name
                                        </th>
                                        <th class="text-center text-uppercase text-secondary font-weight-bolder text-dark">
                                            IP Permission</th>
                                        <th class="text-center text-uppercase text-secondary font-weight-bolder text-dark">
                                            Permission</th>
                                        <th class="text-center text-uppercase text-secondary font-weight-bolder text-dark">
                                            Action</th>
                                    </tr>
                                </thead>
                                <tbody id="DataTableBody">
                                    @foreach ($Users as $user)
                                        <tr>
                                            <td>{{ $user->id}}</td>
                                            <td class="encrypted-data">{{ $user->name }}</td>
                                            <td class="encrypted-data ">
                                                {{ $user->exchange->name ?? 'No Exchange' }}</td>
                                            <td class="">
                                                <form action="{{ route('admin.user.ip_allow') }}" method="POST"
                                                    class="toggle-form d-flex justify-content-center">
                                                    @csrf
                                                    <input type="hidden" name="userId" value={{ $user->id }}>
                                                    @if ($user->ipAddress && $user->ipAddress->status === 'active')
                                                        <input type="button" class="btn btn-success" value="Active">
                                                    @else
                                                        <input type="submit" class="btn btn-secondary" value="Inactive">
                                                    @endif
                                                </form>
                                            </td>
                                            <td class="">
                                                <form action="{{ route('admin.user.status') }}" method="POST"
                                                    class="toggle-form d-flex justify-content-center">
                                                    @csrf
                                                    <input type="hidden" name="userId" value="{{ $user->id }}">
                                                    <input type="hidden" name="status" value="{{ $user->status }}">

                                                    <input type="checkbox" id="checkbox-{{ $user->id }}"
                                                        name="status_toggle"
                                                        {{ $user->status === 'active' ? 'checked' : '' }}
                                                        onchange="toggleStatus(this)">

                                                    <label for="checkbox-{{ $user->id }}" class="button">
                                                        <div class="dot"></div>
                                                    </label>
                                                </form>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center ">
                                                    <form method="POST" action="{{ route('admin.user.delete') }}"
                                                        class="">
                                                        @csrf
                                                        <input type="hidden" id="deleteIdInput" name="id"
                                                            value="{{ $user->id }}">
                                                        <button type="submit"
                                                            class="btn btn-danger btn-sm px-4">Delete</button>
                                                    </form>
                                                    <button type="button" class="btn btn-primary btn-sm px-2 mx-3"
                                                        onclick="loadUserHandledData({{ $user->id }})">Performance</button>
                                                    <button type="button" class="btn btn-info btn-sm edit-button px-4"
                                                        data-id="{{ $user->id }}" data-user-name="{{ $user->name }}"
                                                        data-user-email="{{ $user->email }}">
                                                        Edit
                                                    </button>
                                                </div>
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
                    <h5 class="modal-title" id="myModalLabel" style="color:white">Add New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success text-white" id='success' style="display:none;"></div>
                    <div class="alert alert-danger text-white" id='error' style="display:none;"></div>
                    <form id="form" method="post" action="{{ route('admin.user.formPost') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="editExchange" class="form-label">Exchange</label>
                            <select class="form-select px-3" id="editExchange" name="exchange_id">
                                <option value="" disabled selected>Select Exchange</option>
                                @foreach ($Exchanges as $exchange)
                                    <option value="{{ $exchange->id }}" class="exchange-option encrypted-data">
                                        {{ $exchange->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="user_name" class="form-label">User Name</label>
                            <input type="text" class="form-control border px-3" id="user_name" name="user_name"
                                placeholder="Enter Username" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">User Email</label>
                            <input type="text" class="form-control border px-3" id="email" name="email"
                                placeholder="Enter Email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control border px-3" id="password" name="password"
                                placeholder="Enter Password" required>
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




    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editUserForm" method="post" action="{{ route('admin.user.update') }}">
                        @csrf
                        <input type="hidden" id="editUserId" name="id">
                        <div class="mb-3">
                            <label for="editUserName" class="form-label">User Name</label>
                            <input type="text" class="form-control" id="editUserName" name="name">
                        </div>
                        <div class="mb-3">
                            <label for="editUserEmail" class="form-label">User Email</label>
                            <input type="text" class="form-control" id="editUserEmail" name="email">
                        </div>
                        <div class="mb-3">
                            <label for="editPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="editPassword" name="password"
                                placeholder="Enter New Password">
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


    <!-- User Report Modal -->
    <div class="modal fade" id="userReportModal" tabindex="-1" aria-labelledby="userReportModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h5 class="modal-title" id="userReportModalLabel" style="color:white">User Performance</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body col-11">
                    <canvas id="userHandledChart"></canvas>
                </div>
            </div>
        </div>
    </div>


    <style>
        @import url("https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;700&display=swap");


        input[type="checkbox"] {
            display: none;
        }

        input[type="checkbox"]:checked+.button {
            filter: none;
        }

        input[type="checkbox"]:checked+.button .dot {
            left: calc(100% - 1.7rem);
            /* Adjust this value */
            background-color: #acc301;
        }

        .button {
            position: relative;
            width: 3.5rem;
            height: 1.6rem;
            border-radius: 1rem;
            box-shadow: inset 2px 2px 5px rgba(0, 0, 0, 0.3), inset -2px -2px 5px rgba(255, 255, 255, 0.8);
            cursor: pointer;
        }

        .button .dot {
            position: absolute;
            width: 1.4rem;
            height: 1.4rem;
            left: 0.25rem;
            top: 50%;
            transform: translateY(-50%);
            border-radius: 50%;
            box-shadow: 3px 3px 6px rgba(0, 0, 0, 0.3), -3px -3px 6px rgba(255, 255, 255, 0.8);
            transition: all 0.3s;
            background-color: #f10f0f;
            will-change: left, background-color;
        }

        @keyframes deco-move {
            to {
                transform: translate(-50%, -50%) rotate(360deg);
            }
        }
    </style>



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

            const editButtons = document.querySelectorAll('.edit-button');
            const editModal = new bootstrap.Modal(document.getElementById('editUserModal'));

            // Handle click event for Edit buttons
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const userId = button.getAttribute('data-id');
                    const userName = decryptData(button.getAttribute('data-user-name'));
                    const userEmail = decryptData(button.getAttribute('data-user-email'));
                    const exchangeId = button.getAttribute('data-exchange-id');

                    // Set values in the modal
                    $('#editUserId').val(userId);
                    $('#editUserName').val(userName);
                    $('#editUserEmail').val(userEmail);
                    $('#editExchange').val(exchangeId);
                    $('#editPassword').val('');

                    // Show the modal
                    editModal.show();
                });
            });


            $('#editUserForm').on('submit', function(e) {
                e.preventDefault();

                try {
                    const userId = $('#editUserId').val();
                    const userName = encryptData($('#editUserName').val());
                    const userEmail = encryptData($('#editUserEmail').val());
                    const password = encryptData($('#editPassword').val());
                    const exchangeId = $('#editExchange').val();

                    // Set encrypted values back to the form
                    $('#editUserId').val(userId);
                    $('#editUserName').val(userName);
                    $('#editUserEmail').val(userEmail);
                    $('#editPassword').val(password);
                    $('#editExchange').val(exchangeId);

                    // Submit the form
                    this.submit();
                } catch (error) {
                    console.error("Error encrypting data:", error);
                }
            });




            $('[data-toggle="toggle"]').bootstrapToggle();



            $('[id^="status-"]').on('change', function() {
                const userId = $(this).data('user-id');
                const status = $(this).prop('checked');

                togglePermission(userId, status);
            });


        });

        function toggleStatus(checkbox) {
            const form = checkbox.closest('form');
            const statusInput = form.querySelector('input[name="status"]');
            statusInput.value = checkbox.checked ? 'active' : 'deactive';
            form.submit();
        }

        function toggleStatus1(checkbox) {
            const form = checkbox.closest('form1');
            const statusInput = form.querySelector('input[name="ip_check"]');
            statusInput.value = checkbox.checked ? '' : 'allow';
            form.submit();
        }

        async function loadUserHandledData(userId) {
            const formData = new FormData();
            formData.append('id', userId);

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Send a POST request to the backend route
            const response = await fetch("{{ route('admin.user.performance') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: formData,
            });

            // Parse the JSON response
            const data = await response.json();
            console.log(data);
            // Prepare data for the line chart
            const labels = ['Complaints', 'Follow Ups', 'Refer IDs', 'Rejects', 'Demo Sends', 'New IDs'];
            const userData = [
                data.complaints,
                data.followUps,
                data.referIds,
                data.rejects,
                data.demoSends,
                data.newIds,
            ];

            // Clear previous chart instance if it exists
            if (window.userChart) {
                window.userChart.destroy();
            }
            const ctx = document.getElementById('userHandledChart').getContext('2d');

            // Create a new line chart
            window.userChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'User Performance',
                        data: userData,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.1, // Smoothness of the line
                        pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: 'rgba(54, 162, 235, 1)',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'User Performance Overview',
                            font: {
                                size: 18
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                        },
                        legend: {
                            display: true,
                            position: 'top',
                        }
                    },
                    interaction: {
                        mode: 'nearest',
                        axis: 'x',
                        intersect: false
                    },
                    scales: {
                        x: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Categories'
                            }
                        },
                        y: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Count'
                            },
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });


            const reportModal = new bootstrap.Modal(document.getElementById('userReportModal'));
            reportModal.show();
        }
    </script>
@endsection
