@include('layouts.title', ['title'=>'Profile'])

@extends ('home')

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
                                @if(session()->has('message'))
                                    <div class="alert alert-success">
                                        {{ session()->get('message') }}
                                    </div>
                                @elseif (session()->has('failure'))
                                    <div class="alert alert-danger">
                                        {{ session()->get('failure') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                    <form class="form-horizontal" method="POST" action="{{ route('updateprofile') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('firstname') ? ' has-error' : '' }}">
                            <label for="firstname" class="col-md-4 control-label">First Name</label>

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
                            <label for="lastname" class="col-md-4 control-label">Last Name</label>

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
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

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

                        <hr>

                        @if (!empty($organisations))
                            <h4>Memberships</h4>
                        @endif
                        @foreach ($organisations as $organisation)

                            <div class="form-group">
                                <a href="{{route('updateusermembershipview', $organisation->usermembershipid)}}">
                                    <label for="organsationname" class="col-md-4 control-label">{{$organisation->name}}</label>
                                </a>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="code" disabled placeholder="{{$organisation->membershipcode ?? ''}}">Membership Code
                                </div>

                            </div>

                            <hr>
                        @endforeach


                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-success" value="update" name="submit">
                                    Update
                                </button>

                                <button type="submit" class="btn btn-primary" value="add" name="submit">
                                    Add Membership Code
                                </button>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

{{-- </div> --}}
@endsection
