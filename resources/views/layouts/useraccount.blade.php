@if (Auth::check())
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

        <li class="user-footer">
            <div class="pull-left">
                <a href="/profile" class="btn btn-default btn-flat">Profile</a>
            </div>
            <div class="pull-right">
                <a href="/logout" class="btn btn-default btn-flat">Sign out</a>
            </div>
        </li>
    </ul>
</li>
@else
<li class="dropdown user user-menu">
    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
        <img src="img/avatargrey160x160.png" class="user-image" alt="User Image">
    </a>

</li>
@endif