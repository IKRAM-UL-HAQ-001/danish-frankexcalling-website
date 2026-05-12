<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl mt-2" id="navbarBlur"
    data-scroll="false">
    <div class="container-fluid py-1 px-3 d-flex flex-row">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-12 me-5">
                @if (session()->has('user_role'))
                    @switch(session('user_role'))
                        @case('admin')
                            <li class="breadcrumb-item text-sm">
                                <a class="text-white mb-2" href="javascript:void(0);">Admin</a>
                            </li>
                            @if(session('exchange'))
                            <li class="breadcrumb-item text-sm">
                                <a class="text-white mb-2" href="javascript:void(0);">{{ session('exchange') }}</a>
                            </li>
                            @endif
                            <li class="breadcrumb-item text-sm text-white active" aria-current="page">Dashboard</li>
                        @break

                        @case('assistant')
                            <li class="breadcrumb-item text-sm">
                                <a class="text-white" href="javascript:void(0);">Assistant</a>
                            </li>
                            <li class="breadcrumb-item text-sm text-white active" aria-current="page">Dashboard</li>
                        @break

                        @case('exchange')
                            <li class="breadcrumb-item text-sm">
                                <a class="text-white" href="javascript:void(0);" style="font-size:18px">Exchange Dashboard</a>
                            </li>
                        @break

                        @case('customercare')
                            <li class="breadcrumb-item text-sm">
                                <a class="text-white" href="javascript:void(0);" style="font-size:18px">Customer Care
                                    Dashboard</a>
                            </li>
                        @break
                    @endswitch
                @endif
            </ol>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4 d-flex justify-content-end mt-4"
            id="navbar">
            <ul class="navbar-nav d-flex align-items-center justify-content-end">
                <li class="nav-item dropdown pe-2 d-flex align-items-center">
                    @if (session()->has('user_role'))
                        @if (session('user_role') === 'admin')
                            <a href="javascript:void(0);" class="d-inline text-white btn py-2 mt-3"
                                style="background-color: #2a2a2a; margin-right: 12px; font-size:16px !important;"
                                onclick="confirmLogout()">
                                Logout All
                            </a>
                            <a href="javascript:void(0);" class="d-inline btn text-white py-2 mt-3"
                                style="background-color: #2a2a2a; margin-right: 12px; font-size:16px !important;"
                                onclick="confirmDownload()">
                                Download Database
                            </a>

                            <script type="text/javascript">
                                function confirmDownload() {
                                    if (confirm("Are you sure you want to download the database?")) {
                                        window.location.href = "{{ route('database.export') }}";
                                    }
                                }
                            </script>
                        @endif
                    @endif
                    <a class="nav-link text-body p-0" id="dropdownMenuButton" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <span class="d-sm-inline encrypted-data" style="color:white">{{ session('name') }}</span>
                        <i class="fa fa-user cursor-pointer d-none d-sm-inline"
                            style="color:white; margin-left: 8px;"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
                        @if (session()->has('user_role'))
                            @if (session('user_role') === 'admin')
                                <li class="mb-2">
                                    <a class="dropdown-item border-radius-md" data-bs-toggle="modal"
                                        data-bs-target="#updatePasswordModal">
                                        <div class="d-flex align-items-center py-2">
                                            <i class="fas fa-lock me-2"></i>
                                            <div>
                                                <h6 class="text-sm font-weight-normal mb-0">
                                                    <span class="font-weight-bold">Update Password</span>
                                                </h6>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @endif
                        @endif
                        <li class="mb-2">
                            <a class="dropdown-item border-radius-md" href="{{ route('login.logout') }}">
                                <div class="d-flex align-items-center py-2">
                                    <i class="fas fa-sign-out-alt me-2"></i>
                                    <div>
                                        <h6 class="text-sm font-weight-normal mb-0">
                                            <span class="font-weight-bold">Logout</span>
                                        </h6>
                                    </div>
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                    <a class="nav-link text-white p-0" id="iconNavbarSidenav" style="cursor: pointer;">
                        <div class="sidenav-toggler-inner">
                            <i class="sidenav-toggler-line bg-white"></i>
                            <i class="sidenav-toggler-line bg-white"></i>
                            <i class="sidenav-toggler-line bg-white"></i>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Modal -->
<div class="modal fade" id="updatePasswordModal" tabindex="-1" role="dialog"
    aria-labelledby="updatePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white" id="updatePasswordModalLabel">Update Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updatePasswordForm" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="currentPassword">Current Password</label>
                        <input type="password" class="form-control" id="currentPassword" name="currentPassword"
                            placeholder="Enter Old Password" required>
                    </div>
                    <div class="form-group">
                        <label for="newPassword">New Password</label>
                        <input type="password" class="form-control" id="newPassword" name="newPassword"
                            placeholder="Enter New Password" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="submitPasswordUpdate()">Update
                    Password</button>
            </div>
        </div>
    </div>
</div>

<script>
    function submitPasswordUpdate() {
        var formData = {
            currentPassword: encryptData($('#currentPassword').val()),
            newPassword: encryptData($('#newPassword').val()),
            _token: '{{ csrf_token() }}'
        };

        $.ajax({
            url: '{{ route('password.update') }}',
            type: 'POST',
            data: formData,
            success: function(response) {
                alert('Password updated successfully!');
                $('#updatePasswordModal').modal('hide');
                location.reload();
            },
            error: function(xhr) {
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    alert(xhr.responseJSON.message);
                } else {
                    alert('An error occurred while updating the password.');
                }
            }
        });
    }

    function confirmLogout() {
        if (confirm("Are you sure you want to log out of all users?")) {
            window.location.href = "{{ route('logout.all') }}";
        }
    }
    document.getElementById('iconNavbarSidenav').addEventListener('click', function() {
        var sidebar = document.getElementById('sidenav-main');
        sidebar.classList.toggle('show-sidebar');
    });
    document.addEventListener('click', function(event) {
        var sidebar = document.getElementById('sidenav-main');
        var toggleButton = document.getElementById('iconNavbarSidenav');
        if (sidebar.classList.contains('show-sidebar') && !sidebar.contains(event.target) && !toggleButton
            .contains(event.target)) {
            sidebar.classList.remove('show-sidebar');
        }

    });
</script>
