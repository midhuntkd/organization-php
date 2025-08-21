<aside class="main-sidebar">
    <!-- sidebar-->
    <section class="sidebar position-relative">
        <div class="multinav">
            <div class="multinav-scroll" style="height: 99%;">
                <!-- sidebar menu-->
                <ul class="sidebar-menu" data-widget="tree">
                    <li class="header fs-10 m-0 text-uppercase">Dashboard</li>
                    @role('organization-admin')
                    <li>
                        <a href="{{ route('orgadmin.dashboard', $organization->slug) }}">
                            <i data-feather="home"></i>
                            <span>Dashboard</span>
                            <!-- <span class="pull-right-container">
                                <i class="fa fa-angle-right pull-right"></i>
                            </span> -->
                        </a>
                    </li>
                    <!-- <li class="header fs-10 m-0 text-uppercase">Components</li> -->
                    <li class="treeview">
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
                    </li>
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
                        </ul>
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
                </ul>

                <div class="sidebar-widgets">
                    <div class="mx-25 mb-30 pb-5 bg-primary-light rounded-5">
                        <div class="text-center">
                            <img src="{{ asset('hyper/images/gadget_people_800x600.gif') }}" class="sideimg p-5 rounded-5" alt="">
                            @role('organization-admin')
                            <h4 class="title-bx text-black m-0">Admin</h4>
                            @endrole
                            @role('member') 
                            <h4 class="title-bx text-black m-0">Member</h4>
                            @endrole
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</aside>