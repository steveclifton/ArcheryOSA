@if($user)
<li class="dropdown user user-menu">
    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
        <img src="img/steve160x160.jpg" class="user-image" alt="User Image">
        <span class="hidden-xs">Steve Clifton</span>
    </a>
    <ul class="dropdown-menu">
        <li class="user-header">
            <img src="img/steve160x160.jpg" class="img-circle" alt="User Image">
            <p>
                {{$userName}}
            </p>
        </li>
        <!-- Menu Body -->
        {{-- <li class="user-body"> --}}
            {{-- <div class="row"> --}}
                {{--<div class="col-xs-4 text-center">
                    <a href="#">My Events</a>
                </div> --}}
            {{-- </div> --}}
        {{-- </li> --}}
        <!-- Menu Footer-->
        <li class="user-footer">
            <div class="pull-left">
                <a href="#" class="btn btn-default btn-flat">Profile</a>
            </div>
            <div class="pull-right">
                <a href="#" class="btn btn-default btn-flat">Sign out</a>
            </div>
        </li>
    </ul>
</li>
@else
<li class="dropdown user user-menu">
    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
        <img src="img/avatargrey160x160.png" class="user-image" alt="User Image">
        <span class="hidden-xs">Sign in</span>
    </a>
    <ul class="dropdown-menu">
        <li class="user-header">
            <img src="img/avatargrey160x160.png" class="img-circle" alt="User Image">
            <p>
                Sign in
            </p>
        </li>
        <li class="user-footer">
            <div class="pull-left">
                <div class="input-group input-group-lg">
                  <input type="email" class="form-control" placeholder="Username" aria-describedby="sizing-addon1">
                </div>
                <div class="input-group input-group-lg">
                  <input type="password" class="form-control" placeholder="Username" aria-describedby="sizing-addon1">
                </div>
            </div>
        </li>
    </ul>
</li>
@endif