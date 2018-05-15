
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
        @if (Auth::check())
            <div class="pull-left image">
            <?php if (!empty(Auth::user()->image)) {  ?>
                <img src="/content/profile/{{Auth::user()->image}}" class="img-circle" >
            <?php } else { ?>
                <img src="{{URL::asset('/image/avatargrey160x160.png')}}" class="img-circle" >
            <?php } ?>
            </div>
            <div class="pull-left info">
                <p>
                    <a href="{{route('profile')}}">
                    {!! htmlentities(ucfirst(Auth::user()->firstname)) . " " . htmlentities(ucfirst(Auth::user()->lastname)) !!}
                    </a>
                </p>
            </div>
        @else
            <div class="pull-left image">
                <img src="{{URL::asset('/image/avatargrey160x160.png')}}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p><a href="/login">Login</a></p>
            </div>
        @endif
        </div>

        <ul class="sidebar-menu" data-widget="tree">

            @if (Auth::check() && Auth::user()->usertype == 1)
                @include('includes.adminsidebar')
            @endif

        @if (Auth::check())
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-dashboard"></i>
                    <span>My Dashboard</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="active margin15" >
                        <a href="{{route('profile')}}">
                            <i class="fa fa-bullseye"></i>My Profile
                        </a>
                        <a href="{{route('myevents')}}">
                            <i class="fa fa-bullseye"></i>My Events
                        </a>
                        <a href="javascript:;">
                            <i class="fa fa-bullseye"></i>My Results
                        </a>
                    </li>
                </ul>
            </li>
        @else
            <li class="treeview">
                <li class="margin15">
                    <a href="{{ route('register')}}">
                        <i class="fa fa-user-plus"></i><span>Join ArcheryOSA!</span>
                    </a>
                </li>
            </li>
        @endif


        <li class="treeview">
            <a href="#">
                <i class="fa fa-edit"></i> <span>Competitions</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li class="margin15"><a href="/upcomingevents"><i class="fa fa-calendar-check-o"></i>Upcoming Events</a></li>
                <li class="margin15"><a href="/previousevents"><i class="fa fa-calendar-check-o"></i>Previous Events</a></li>
            </ul>
        </li>

        @if ( !empty(Auth::check()) && Auth::user()->usertype == 1 )
            <li class="treeview menu-open">
                <a href="javascript:;">
                    <i class="fa fa-cog"></i> <span>Admin</span>
                    <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
                </a>
                <ul class="treeview-menu" style="display: block">
                    <li class="margin15"><a href="{{route('events')}}"><i class="fa fa-cog"></i>Manage Events</a></li>
                </ul>
            </li>
        @endif

        </ul>
    </section>
    <!-- /.sidebar -->
</aside>