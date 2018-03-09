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
                    <a href="{{route('eventdetails', urlencode($event->name)) }}">
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
                                    <select name="divisions" class="form-control" id="organisation" required>
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

                                <div class="col-md-6" style="overflow-y:scroll; height:200px; margin-bottom:10px;">

                                    @foreach ($divisions as $division)
                                        <input class="form-check-input" type="checkbox" name="divisions[]" value="{{$division->divisionid}}" >
                                        <label class="form-check-label" style="margin-left: 10px" >{{$division->name}}</label><br>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if ($event->ignoregenders == 0)
                        <div class="form-group {{ $errors->has('division') ? ' has-error' : '' }}" id="gender">

                                <label for="gender" class="col-md-4 control-label">Gender</label>
                                <div class="col-md-6">
                                    <input class="form-check-input" type="radio" name="gender" value="M">
                                    <label class="form-check-label" style="margin-left: 10px">Mens</label><br>
                                    <input class="form-check-input" type="radio" name="gender" value="F">
                                    <label class="form-check-label" style="margin-left: 10px">Womens</label>
                                </div>

                        </div>

                        @endif

                        <div class="form-group {{ $errors->has('division') ? ' has-error' : '' }}" id="Rounds">

                            @if ($event->eventtype == 1)
                                <label for="Rounds" class="col-md-4 control-label">Round</label>
                                <div class="col-md-6">
                                    @foreach ($eventround as $round)
                                        <input class="form-check-input" type="checkbox" checked disabled name="eventroundid" value="{{$round->eventroundid}}" >
                                        <label class="form-check-label" style="margin-left: 10px">{!! $round->name !!}</label>
                                    @endforeach
                                </div>

                            @else
                                <label for="Rounds" class="col-md-4 control-label">Rounds</label>
                                <div class="col-md-6">
                                    @foreach ($eventround as $round)
                                        <input class="form-check-input" type="checkbox" name="eventroundid[]" value="{{$round->eventroundid}}" >
                                        <label class="form-check-label" style="margin-left: 10px" >{!! date('d F', strtotime($round->date)) . " - " .$round->name!!}</label><br>
                                    @endforeach
                                </div>
                            @endif
                        </div>




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
                                <textarea rows="3" id="address" type="text" class="form-control" name="address" >{{ Auth::user()->address ?? old('address') }}</textarea>
                                @if ($errors->has('address'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('address') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('notes') ? ' has-error' : '' }}">
                            <label for="address" class="col-md-4 control-label">Notes</label>

                            <div class="col-md-6">
                                <textarea rows="5" id="address" type="text" class="form-control" name="notes" ></textarea>
                                @if ($errors->has('notes'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('notes') }}</strong>
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


