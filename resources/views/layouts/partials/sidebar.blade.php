<style>
    .theme-primary .bg-primary-light {
        background-color: #7f8289 !important;
        color: #0052cc;
    }
</style>

<aside class="main-sidebar">
    <!-- sidebar-->
    <section class="sidebar position-relative">
        <div class="multinav">
            <div class="multinav-scroll" style="height: 99%;">
                <!-- sidebar menu-->
                <ul class="sidebar-menu" data-widget="tree">
                    {{-- <li class="header fs-10 m-0 text-uppercase">Menu</li> --}}
                    @role('organization-admin') 
                    <li class="{{ request()->routeIs('orgadmin.dashboard') ? 'active' : '' }}">
                        <a href="{{ route('orgadmin.dashboard', $organization->slug) }}">
                            <i data-feather="home"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    @endrole

                    @role('member')
                    <li>
                        <a href="{{ route('member.dashboard', $organization->slug) }}">
                            <i data-feather="home"></i>
                            <span>Dashboard</span>
                            <!-- <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span> -->
                        </a>
                    </li>
                    @endrole
                    @if(auth()->user()->hasRole('organization-admin') || auth()->user()->can('access.members') || auth()->user()->can('access.memberships') || auth()->user()->can('access.upcoming_modules'))
                    
                    @if(auth()->user()->hasRole('organization-admin') || auth()->user()->can('access.members'))
                        <li class="treeview">
                            <a href="#">
                                <i data-feather="box"></i>
                                <span>Members</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-right pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{ route('orgadmin.members.myapprovals', $organization->slug) }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>My Approvals</a></li>
                                <li><a href="{{ route('orgadmin.members', $organization->slug) }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Members</a></li>
                                <li><a href="{{ route('orgadmin.members.permissions', $organization->slug) }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Permissions</a></li>
                            </ul>
                        </li>
                    @endif
                    <!-- <li class="header fs-10 m-0 text-uppercase">Components</li> -->
                    <!-- <li class="treeview">
                        <a href="#">
                            <i data-feather="box"></i>
                            <span>Membership Categories</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{{ route('orgadmin.membership-categories.index', $organization->slug) }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>List</a></li>
                            <li><a href="{{ route('orgadmin.membership-categories.create', $organization->slug) }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Add New</a></li>
                        </ul>
                    </li> -->
                    @if(auth()->user()->hasRole('organization-admin') || auth()->user()->can('access.memberships'))
                        <li class="treeview">
                            <a href="#">
                                <i data-feather="credit-card"></i>
                                <span>Membership Plans</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-right pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{ route('orgadmin.memberships.index', $organization->slug) }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>List</a></li>
                                <li><a href="{{ route('orgadmin.memberships.create', $organization->slug) }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Add New</a></li>
                                <li><a href="{{ route('orgadmin.membership_upgrades.index', $organization->slug) }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Upgrade Approvals</a></li>
                            </ul>
                        </li>

                        <li class="treeview">
                            <a href="#">
                                <i data-feather="credit-card"></i>
                                <span>Membership Benefits</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-right pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{ route('orgadmin.membership_benefits.index', $organization->slug) }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>List</a></li>
                                <li><a href="{{ route('orgadmin.membership_benefits.create', $organization->slug) }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Add New</a></li>
                            </ul>
                        </li>

                        <li class="treeview">
                            <a href="#">
                                <i data-feather="credit-card"></i>
                                <span>Membership Rules</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-right pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{ route('orgadmin.membership_rules.index', $organization->slug) }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>List</a></li>
                                <li><a href="{{ route('orgadmin.membership_rules.create', $organization->slug) }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Add New</a></li>
                            </ul>
                        </li>

                        @if(auth()->user()->hasRole('organization-admin') || auth()->user()->can('access.payments'))
                        <li>
                            <a href="{{ route('orgadmin.transactions.index', $organization->slug) }}">
                                <i data-feather="list"></i>
                                <span>Transactions</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('orgadmin.payments.index', $organization->slug) }}">
                                <i data-feather="dollar-sign"></i>
                                <span>Payments approvals</span>
                            </a>
                        </li>
                        @endif
                    @endif

                    @endif
                    @role('member')
                    <li>
                        <a href="{{ route('member.profile.edit', $organization->slug) }}">
                            <i data-feather="user"></i>
                            <span>Update Profile</span>
                            <!-- <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span> -->
                        </a>
                    </li>
                    @endrole
                    @hasrole('super-admin')
                    {{-- <li class="treeview {{ request()->is('super-admin/organizations*') ? 'menu-open active' : '' }}">
                        <a href="#">
                            <i class="mdi mdi-office-building"></i> 
                            <span>Organizations</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu" style="{{ request()->is('super-admin/organizations*') ? 'display:block;' : '' }}">
                            <li class="{{ request()->routeIs('superadmin.organizations.index') ? 'active' : '' }}">
                                <a href="{{ route('superadmin.organizations.index') }}">
                                    <i class="ti-list"></i> All Organizations
                                </a>
                            </li>
                            <li class="{{ request()->routeIs('superadmin.organizations.create') ? 'active' : '' }}">
                                <a href="{{ route('superadmin.organizations.create') }}">
                                    <i class="ti-plus"></i> Add Organization
                                </a>
                            </li>
                        </ul>
                    </li> --}}


                    <li >
                        <a href="{{ route('superadmin.organizations.index') }}">
                            <i data-feather="credit-card"></i>
                            <span>Organizations</span>
                            {{-- <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span> --}}
                        </a>
                        {{-- <ul class="treeview-menu">
                            <li class="{{ request()->routeIs('superadmin.organizations.index') ? 'active' : '' }}"><a href="{{ route('superadmin.organizations.index') }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>List</a></li>
                            <li class="{{ request()->routeIs('superadmin.organizations.create') ? 'active' : '' }}"><a href="{{ route('superadmin.organizations.create') }}"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Add New</a></li>
                        </ul> --}}
                    </li>
                    @endhasrole

                    <li>
                        <a href="{{ route('account.password.change') }}">
                            <i data-feather="lock"></i>
                            <span>Change Password</span>
                        </a>
                    </li>
                </ul>

                <div class="sidebar-widgets">
                    <div class="mx-25 mb-30 pb-5 bg-primary-light rounded-5">
                        <div class="text-center">
                            
                            @role('organization-admin')
                            <img src="{{ $organization->logo_url }}" class="sideimg p-5 rounded-5" alt="">
                            <h4 class="title-bx text-black m-0">Admin</h4>
                            @endrole
                            @role('member')
                            <img src="{{ $organization->logo_url }}" class="sideimg p-5 rounded-5" alt="">
                            <h4 class="title-bx text-black m-0">Member</h4>
                            @endrole
                            @role('super-admin')
                            <img src="{{ asset('hyper/images/gadget_people_800x600.gif') }}" class="sideimg p-5 rounded-5" alt="">
                            <h4 class="title-bx text-black m-0">Super Admin</h4>
                            @endrole
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</aside>
