@extends ('home')

@section ('title')Profile @endsection

@section ('content')

{{-- {!! dd(request()) !!} --}}

{{-- <div class="container"> --}}
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">My Profile</div>
                <div class="panel-body">
                    @if (session('key'))
                        <div class="alert alert-success">
                            {{ session('key') }}
                        </div>
                    @endif

                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                                @include('includes.session_errors')
                            </div>
                        </div>

                    <form class="form-horizontal" method="POST" action="{{ route('updateprofile') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('firstname') ? ' has-error' : '' }}">
                            <label for="firstname" class="col-md-4 control-label">First Name*</label>

                            <div class="col-md-6">
                                <input id="firstname" type="text" class="form-control" name="firstname" value="{{ (old('firstname')) ? old('firstname') : $user->firstname }}" required autofocus>

                                @if ($errors->has('firstname'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('firstname') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('lastname') ? ' has-error' : '' }}">
                            <label for="lastname" class="col-md-4 control-label">Last Name*</label>

                            <div class="col-md-6">
                                <input id="lastname" type="text" class="form-control" name="lastname" value="{{ (old('lastname')) ? old('lastname') : $user->lastname }}" required autofocus>

                                @if ($errors->has('lastname'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('lastname') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address*</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ (old('email')) ? old('email') : $user->email }}" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                            <label for="phone" class="col-md-4 control-label">Phone</label>

                            <div class="col-md-6">
                                <input id="phone" type="text" class="form-control" name="phone" value="{{ (old('phone')) ? old('phone') : $user->phone }}">

                                @if ($errors->has('phone'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('profileimage') ? ' has-error' : '' }}">
                            <label for="profileimage" class="col-md-4 control-label">Profile Image</label>

                            <div class="col-md-6">
                                <input id="profileimage" type="file" class="form-control" name="profileimage">
                                @if (!empty($user->image))
                                    <img id="blah" src="/content/profile/{{ (old('image')) ? old('image') : $user->image }}" alt="" style="width: 150px" />
                                @endif

                                @if ($errors->has('profileimage'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('profileimage') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-10 ">
                                <button type="submit" class="btn btn-success pull-left" value="update" name="submit">
                                    Update Profile
                                </button>
                            </div>
                        </div>
                        <br>


                        <div class="">
                            <hr>
                            <h4>Memberships</h4>
                            <a href="{{route('createusermembershipview')}}" class="btn btn-primary" role="button">Add Membership</a>

                        </div>
                        @foreach ($organisations as $organisation)
                            <div class="form-group">
                                <label for="organsationname" class="col-md-4 control-label">{{$organisation->name}}</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="code" disabled placeholder="{{$organisation->membershipcode ?? ''}}">Membership Code
                                </div>
                                <a href="{{route('updateusermembershipview', $organisation->usermembershipid)}}" class="btn btn-warning" role="button">Update</a>
                            </div>
                        @endforeach




                        <div>
                            <hr>
                            <h4>Archer Relations</h4>
                            <a href="{{route('createaddarcherview')}}" class="btn btn-primary" role="button">Add Archer</a>
                        </div>
                        @foreach ($relationships as $relation)
                            <div class="form-group">
                                <label for="organsationname" class="col-md-4 control-label">{{$relation->firstname}} {{$relation->lastname}}</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="code" disabled placeholder="{!! ($relation->authorised) ? 'AUTHORISED' : 'PENDING' !!}">Status
                                </div>
                                <a href="{{route('removeuserrelation', $relation->hash)}}" class="btn btn-danger" role="button" id="deleteUserRelation">Remove</a>
                            </div>
                        @endforeach

                    </form>
                </div>
            </div>
        </div>
    </div>

{{-- </div> --}}
@endsection
