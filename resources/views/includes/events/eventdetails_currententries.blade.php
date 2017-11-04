<div class="box-body">
    <ul class="products-list product-list-in-box">

        @foreach (array_slice($users, 0, 10) as $user)
            <li class="item">

                <span style="padding-right: 10%">
                    <span class="label {{ $user->label }}">{{$user->division}}</span>
                </span>

                {{ucwords(strtolower($user->fullname))}}

            </li>
        @endforeach

        @foreach (array_slice($users, 10) as $user)
            <li class="item hidden">

                <span style="padding-right: 10%">
                    <span class="label {{ $user->label }}">{{$user->division}}</span>
                </span>

                {{ucwords(strtolower($user->fullname))}}

            </li>
        @endforeach

    </ul>
</div>