@extends ('home')

@section ('title')User's Profile @endsection

@section ('content')



    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">User Profile</h3>
                </div>
                <!-- /.box-header -->
                <form class="form-horizontal" method="POST" action="{{ route('updateuserprofile', $user->userid) }}" enctype="multipart/form-data">
                    {{ csrf_field() }}

                    <div class="form-group{{ $errors->has('usertype') ? ' has-error' : '' }}">
                        <label for="usertype" class="col-md-4 control-label">User Type*</label>

                        <div class="col-md-6">

                            <select name="usertype" class="form-control" id="organisationselect">
                                @foreach ($usertypes as $usertype)
                                    <option value="{{$usertype->usertypeid}}" @if($user->usertype == $usertype->usertypeid){{'selected'}} @endif>{{ucwords($usertype->name)}}</option>
                                @endforeach

                            </select>
                        </div>

                    </div>

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

                    <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                        <label for="address" class="col-md-4 control-label">Address</label>

                        <div class="col-md-6">

                            <textarea name="address" id="" class="form-control" cols="30" rows="3">{{ (old('address')) ? old('address') : $user->address }}</textarea>

                            @if ($errors->has('address'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('address') }}</strong>
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
                        <div class="checkbox">
                            <label class="col-md-4 control-label">Remove Image</label>
                            <div class="col-md-6">
                                <input type="checkbox" name="visible">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-10 ">
                            <button type="submit" class="btn btn-success pull-right" value="update" name="submit">
                                Update Profile
                            </button>
                        </div>
                    </div>
                    <br>



                </form>

                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
    {{-- </div> --}}
@endsection