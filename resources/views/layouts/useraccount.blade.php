@if (Auth::check())
<li class="dropdown user user-menu">
    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
        <?php if (!empty(Auth::user()->image)) {  ?>
            <img src="/content/profile/{{Auth::user()->image}}" class="user-image" >
        <?php } else { ?>
            <img src="{{URL::asset('image/avatargrey160x160.png')}}" class="user-image" >
        <?php } ?>
        <span class="hidden-xs">{!! htmlentities(ucfirst(Auth::user()->firstname)) . " " . htmlentities(ucfirst(Auth::user()->lastname)) !!}</span>
    </a>
    <ul class="dropdown-menu">
        <li class="user-header">
            <?php if (!empty(Auth::user()->image)) {  ?>
                <img src="/content/profile/{{Auth::user()->image}}" class="img-circle" >
            <?php } else { ?>
                <img src="{{URL::asset('image/avatargrey160x160.png')}}" class="img-circle" >
            <?php } ?>
            <p>
            {!! htmlentities(ucfirst(Auth::user()->firstname)) . " " . htmlentities(ucfirst(Auth::user()->lastname)) !!}
            </p>
        </li>

        <li class="user-footer">
            <div class="pull-left">
                <a href="{{ route('profile') }}" class="btn btn-default btn-flat">Profile</a>
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