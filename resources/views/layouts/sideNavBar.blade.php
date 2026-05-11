@if (session()->has('user_role'))
    @php
        $userRole = session('user_role');
    @endphp
    <aside
        class="sidenav navbar navbar-vertical navbar-expand-xs  border-0 border-radius-xl my-3 fixed-start ms-4  collapse"
        style="z-index: 1;" id="sidenav-main" style="background-color: #2a2a2a">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
                aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand m-0" href="" target="_blank">
                <span class="ms-1 font-weight-bold" style="color: white">Frank Calling Management</span>
            </a>
        </div>
        <hr class="horizontal dark mt-0">
        <div class="collapse navbar-collapse  w-auto " style="height: 80%" id="sidenav-collapse-main">

            <ul class="navbar-nav">
                @if (session('user_role') === 'admin')
                    <li class="nav-item">
                        <a class="nav-link  {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                            href="{{ route('admin.dashboard') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-tv-2 text-white text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link  {{ request()->routeIs('admin.search.list') || request()->routeIs('admin.search.list') ? 'active' : '' }}"
                            href="{{ route('admin.search.list') }}">
                            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-tv-2 text-white text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">DataEntry Search</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.exchange.list') || request()->routeIs('admin.exchange.userlist') ? 'active' : '' }}"
                            href="{{ route('admin.exchange.list') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-calendar-grid-58 text-white text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Calling Users</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.phone_number.list') ? 'active' : '' }}"
                            href="{{ route('admin.phone_number.list') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-credit-card text-white text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Assign Numbers</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.no_of_call.list') ? 'active' : '' }}"
                            href="{{ route('admin.no_of_call.list') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-credit-card text-white text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">No of Call</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.user.list') ? 'active' : '' }}"
                            href="{{ route('admin.user.list') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-credit-card text-white text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">User</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.reject.list') ? 'active' : '' }}"
                            href="{{ route('admin.reject.list') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-credit-card text-white text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Reject</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.walk.list') ? 'active' : '' }}"
                            href="{{ route('admin.walk.list') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-credit-card text-white text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Walk</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.complaint.list') ? 'active' : '' }}"
                            href="{{ route('admin.complaint.list') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-credit-card text-white text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Complain</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.refer_id.list') ? 'active' : '' }}"
                            href="{{ route('admin.refer_id.list') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-credit-card text-white text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Refer ID</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.demo_send.list') ? 'active' : '' }}"
                            href="{{ route('admin.demo_send.list') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-credit-card text-white text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Demo Send</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.follow_up.list') ? 'active' : '' }}"
                            href="{{ route('admin.follow_up.list') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-credit-card text-white text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Follow Up</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.new_id.list') ? 'active' : '' }}"
                            href="{{ route('admin.new_id.list') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-credit-card text-white text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">New Ids</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.rejoin.list') ? 'active' : '' }}"
                            href="{{ route('admin.rejoin.list') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-credit-card text-white text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Rejoin</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.customer_care.exchangelist') || request()->routeIs('admin.customer_care.list') ? 'active' : '' }}"
                            href="{{ route('admin.customer_care.exchangelist') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-credit-card text-white text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Customer Care</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.quaterly_report.list') ? 'active' : '' }}"
                            href="{{ route('admin.quaterly_report.list') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-calendar-grid-58 text-white text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Quaterly Report</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.upload_files.list') ? 'active' : '' }}"
                            href="{{ route('admin.upload_files.list') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-calendar-grid-58 text-white text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Database Files</span>
                        </a>
                    </li>
                @endif

                @if (session('user_role') === 'assistant')
                    <li class="nav-item">
                        <a class="nav-link  {{ request()->routeIs('assistant.dashboard') ? 'active' : '' }}"
                            href="{{ route('assistant.dashboard') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-tv-2 text-white text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Dashboard</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link  {{ request()->routeIs('assistant.search.list') || request()->routeIs('assistant.search.list') ? 'active' : '' }}"
                            href="{{ route('assistant.search.list') }}">
                            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-tv-2 text-white text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">DataEntry Search</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('assistant.exchange.list') ? 'active' : '' }}"
                            href="{{ route('assistant.exchange.list') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-calendar-grid-58 text-white text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Exchange</span>
                        </a>
                    </li>

                    <!-- <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('assistant.phone_number.list') ? 'active' : '' }}"
                            href="{{ route('assistant.phone_number.list') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-credit-card text-white text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Assign Numbers</span>
                        </a>
                    </li> -->

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('assistant.no_of_call.list') ? 'active' : '' }}"
                            href="{{ route('assistant.no_of_call.list') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-credit-card text-white text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">No of Call</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('assistant.user.list') ? 'active' : '' }}"
                            href="{{ route('assistant.user.list') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-credit-card text-white text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">User</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('assistant.reject.list') ? 'active' : '' }}"
                            href="{{ route('assistant.reject.list') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-credit-card text-white text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Reject</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('assistant.complaint.list') ? 'active' : '' }}"
                            href="{{ route('assistant.complaint.list') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-credit-card text-white text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Complain</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('assistant.walk.list') ? 'active' : '' }}"
                            href="{{ route('assistant.walk.list') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-credit-card text-white text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Walk</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('assistant.rejoin.list') ? 'active' : '' }}"
                            href="{{ route('assistant.rejoin.list') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-credit-card text-white text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Rejoin</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('assistant.refer_id.list') ? 'active' : '' }}"
                            href="{{ route('assistant.refer_id.list') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-credit-card text-white text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Refer ID</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('assistant.demo_send.list') ? 'active' : '' }}"
                            href="{{ route('assistant.demo_send.list') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-credit-card text-white text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Demo Send</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('assistant.follow_up.list') ? 'active' : '' }}"
                            href="{{ route('assistant.follow_up.list') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-credit-card text-white text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Follow Up</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('assistant.new_id.list') ? 'active' : '' }}"
                            href="{{ route('assistant.new_id.list') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-credit-card text-white text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">New Ids</span>
                        </a>
                    </li>
                @endif

                @if (session('user_role') === 'exchange')
                    <li class="nav-item">
                        <a class="nav-link  {{ request()->routeIs('exchange.dashboard') ? 'active' : '' }}"
                            href="{{ route('exchange.dashboard') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-tv-2 text-white text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Dashboard</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link  {{ request()->routeIs('exchange.search.list') || request()->routeIs('exchange.search.list') ? 'active' : '' }}"
                            href="{{ route('exchange.search.list') }}">
                            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-tv-2 text-white text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">DataEntry Search</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('exchange.assign_number.list') ? 'active' : '' }}"
                            href="{{ route('exchange.assign_number.list') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-credit-card text-white text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Assign Numbers</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('exchange.no_of_call.list') ? 'active' : '' }}"
                            href="{{ route('exchange.no_of_call.list') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-credit-card text-white text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">No of Call</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('exchange.new_id.list') ? 'active' : '' }}"
                            href="{{ route('exchange.new_id.list') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-credit-card text-white text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">New Id</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('exchange.reject.list') ? 'active' : '' }}"
                            href="{{ route('exchange.reject.list') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-credit-card text-white text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Reject</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('exchange.walk.list') ? 'active' : '' }}"
                            href="{{ route('exchange.walk.list') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-credit-card text-white text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Walk</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('exchange.refer_id.list') ? 'active' : '' }}"
                            href="{{ route('exchange.refer_id.list') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-credit-card text-white text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Refer ID</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('exchange.demo_send.list') ? 'active' : '' }}"
                            href="{{ route('exchange.demo_send.list') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-credit-card text-white text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Demo Send</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('exchange.complaint.list') ? 'active' : '' }}"
                            href="{{ route('exchange.complaint.list') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-credit-card text-white text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Complaint</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('exchange.follow_up.list') ? 'active' : '' }}"
                            href="{{ route('exchange.follow_up.list') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-credit-card text-white text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Follow Up</span>
                        </a>
                    </li>
                @endif

                @if (session('user_role') == 'customercare')
                    <li class="nav-item">
                        <a class="nav-link  {{ request()->routeIs('customer_care.dashboard') ? 'active' : '' }}"
                            href="{{ route('customer_care.dashboard') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-tv-2 text-white text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Dashboard</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link  {{ request()->routeIs('customer_care.search.list') || request()->routeIs('customer_care.search.list') ? 'active' : '' }}"
                            href="{{ route('customer_care.search.list') }}">
                            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-tv-2 text-white text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">DataEntry Search</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('customer_care.assign_number.list') ? 'active' : '' }}"
                            href="{{ route('customer_care.assign_number.list') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-credit-card text-white text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Assign Numbers</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('customer_care.no_of_call.list') ? 'active' : '' }}"
                            href="{{ route('customer_care.no_of_call.list') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-credit-card text-white text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">No of Call</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('customer_care.reject.list') ? 'active' : '' }}"
                            href="{{ route('customer_care.reject.list') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-credit-card text-white text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Reject</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('customer_care.walk.list') ? 'active' : '' }}"
                            href="{{ route('customer_care.walk.list') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-credit-card text-white text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Walk</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('customer_care.complaint.list') ? 'active' : '' }}"
                            href="{{ route('customer_care.complaint.list') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-credit-card text-white text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Complain</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('customer_care.refer_id.list') ? 'active' : '' }}"
                            href="{{ route('customer_care.refer_id.list') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-credit-card text-white text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Refer Id</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('customer_care.demo_send.list') ? 'active' : '' }}"
                            href="{{ route('customer_care.demo_send.list') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-credit-card text-white text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Demo Send</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('customer_care.follow_up.list') ? 'active' : '' }}"
                            href="{{ route('customer_care.follow_up.list') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-credit-card text-white text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Follow Up</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('customer_care.new_id.list') ? 'active' : '' }}"
                            href="{{ route('customer_care.new_id.list') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-credit-card text-white text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">New Id</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('customer_care.rejoin.list') ? 'active' : '' }}"
                            href="{{ route('customer_care.rejoin.list') }}">
                            <div
                                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="ni ni-credit-card text-white text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Rejoin</span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </aside>
@endif
