@section ('dashboard')
    <h1></h1>
@endsection

@extends ('home')

@section ('title')Event Registration @endsection

@section ('content')
        {{--{!! dd($event) !!}--}}
    {{-- <div class="container"> --}}

    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            @include('includes.session_errors')
        </div>
    </div>

    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Event Registration
                    <a href="{{url()->previous()}}">
                        <button type="submit" class="btn btn-default pull-right" id="addevent">
                            <i class="fa fa-backward" > Back</i>
                        </button>
                    </a>
                </div>



                <div class="panel-body">

                    <form class="form-horizontal" method="POST" action="{{ route('eventregistration', ['eventid' => $event->eventid, 'eventname' => urlencode($event->name) ]) }}" id="eventformupdate">
                        {{ csrf_field() }}

                        <input type="text" name="eventid" hidden value="{{$event->eventid}}">
                        <input type="text" name="userid" hidden value="{{ Auth::id() }}">


                        {{--Here you can choose to enrol others for the events--}}


                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{!! old('name') ?? Auth::user()->firstname . " " . Auth::user()->lastname !!}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('clubid') ? ' has-error' : '' }}" id="club">
                            <label for="organisation" class="col-md-4 control-label">Club</label>

                            <div class="col-md-6">
                                <select name="clubid" class="form-control" id="organisation" required autofocus>
                                    <option value="0">None</option>

                                    @foreach ($clubs as $club)
                                        <option value="{{$club->clubid}}">{{$club->name}}</option>
                                    @endforeach

                                </select>
                                @if ($errors->has('clubid'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('clubid') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>



                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Email</label>

                            <div class="col-md-6">
                                <input id="email" type="text" class="form-control" name="email" value="{{ Auth::user()->email }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        @if ($event->multipledivisions == 0)

                        <div class="form-group {{ $errors->has('division') ? ' has-error' : '' }}" id="organisation">
                            <label for="organisation" class="col-md-4 control-label">Division</label>

                            <div class="col-md-6">
                                <select name="divisions[]" class="form-control" id="organisation" required>
                                    <option value="" disabled selected>Please select</option>
                                    @foreach ($divisions as $division)
                                        <option value="{{$division->divisionid}}">{{$division->name}}</option>
                                    @endforeach

                                </select>
                                @if ($errors->has('division'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('division') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        @else
                            <div class="form-group {{ $errors->has('division') ? ' has-error' : '' }}" id="organisation">
                                <label for="organisation" class="col-md-4 control-label">Division</label>

                                <div style="overflow-y:scroll; height:100px; margin-bottom:10px;">

                                    @foreach ($divisions as $division)
                                        <label class="form-check-label" style="margin-left: 10px" >
                                            <input class="form-check-input" type="checkbox" name="divisions[]" value="{{$division->divisionid}}" >
                                            {{$division->name}}
                                        </label><br>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="form-group{{ $errors->has('membershipcode') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">{{$organisationname}} Membership Code</label>

                            <div class="col-md-6">
                                <input id="membershipcode" type="text" class="form-control" name="membershipcode" value="{{$userorgid}}" required autofocus>

                                @if ($errors->has('membershipcode'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('membershipcode') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Phone</label>

                            <div class="col-md-6">
                                <input id="phone" type="text" class="form-control" name="phone" value="{{Auth::user()->phone}}" required autofocus>

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
                                <textarea rows="5" id="address" type="text" class="form-control" name="address" >{{ Auth::user()->address ?? old('address') }}</textarea>
                                @if ($errors->has('address'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('address') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group{{ $errors->has('cost') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Cost</label>

                            <div class="col-md-6">
                                <input id="cost" type="text" class="form-control" name="cost" value="${{$event->cost}}" disabled>

                                @if ($errors->has('cost'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('cost') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group{{ $errors->has('bankaccount') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Events Bank Account</label>

                            <div class="col-md-6">
                                <input id="bankaccount" type="text" class="form-control" name="bankaccount" value="{{$event->bankaccount}}" disabled>

                                @if ($errors->has('bankaccount'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('bankaccount') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary" value="create" name="submit">
                                    Enter
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection


