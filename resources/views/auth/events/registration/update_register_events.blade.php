@section ('dashboard')
    <h1></h1>
@endsection

@extends ('home')

@section ('title')Update Event Registration @endsection

@section ('content')
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            @include('includes.session_errors')
        </div>
    </div>

    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Update Event Registration

                    <a href="{{route('eventdetails', urlencode($event->name)) }}">
                        <button type="submit" class="btn btn-default pull-right" id="addevent">
                            <i class="fa fa-backward" >  Back</i>
                        </button>
                    </a>
                </div>

                <div class="panel-body">

                    <form class="form-horizontal" method="POST" action="{{ route('updateeventregistration', ['eventid' => $event->eventid, 'eventname' => urlencode($event->name)]) }}" >
                        {{ csrf_field() }}
                        <input type="hidden" name="userid" value="{!! $eventregistration->first()->userid ?? old('userid') !!}" >

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label  class="col-md-4 control-label">Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{!! $eventregistration->first()->fullname ?? old('name') !!}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('clubid') ? ' has-error' : '' }}" id="club">
                            <label class="col-md-4 control-label">Club</label>

                            <input type="text" id="userclubvalue" hidden value="{{ $eventregistration->first()->clubid }}">

                            <div class="col-md-6">
                                <select name="clubid" class="form-control" id="clubselect" required autofocus>

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
                            <label class="col-md-4 control-label">Email</label>

                            <div class="col-md-6">
                                <input id="email" type="text" class="form-control" name="email" value="{!! $eventregistration->first()->email ?? old('email') !!}" required autofocus>

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
                                            <option value="{{$division->divisionid}}" <?= (in_array($division->divisionid, $userdivisions)) ? 'selected' : '' ?>>{{$division->name}}</option>
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
                                        <label class="form-check-label" style="margin-left: 10px" >
                                            <input class="form-check-input" type="checkbox" name="divisions[]" value="{{$division->divisionid}}" <?= (in_array($division->divisionid, $userdivisions)) ? 'checked' : '' ?>>
                                            {{$division->name}}
                                        </label><br>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if ($event->ignoregenders == 0)
                            <div class="form-group {{ $errors->has('division') ? ' has-error' : '' }}" id="gender">
                                <label for="gender" class="col-md-4 control-label">Gender</label>
                                <div class="col-md-6">
                                    <input class="form-check-input" type="radio" name="gender" value="M" {!! $eventregistration->first()->gender == 'M' ? 'checked' : '' !!}>
                                    <label class="form-check-label" style="margin-left: 10px">Mens</label><br>
                                    <input class="form-check-input" type="radio" name="gender" value="F" {!! $eventregistration->first()->gender == 'F' ? 'checked' : '' !!}>
                                    <label class="form-check-label" style="margin-left: 10px">Womens</label>
                                </div>
                            </div>
                        @endif



                        <div class="form-group {{ $errors->has('division') ? ' has-error' : '' }}" id="Rounds">


                            @if ($event->eventtype == 1)

                                <label for="Rounds" class="col-md-4 control-label">Round</label>
                                <div class="col-md-6">
                                @foreach ($eventrounds as $round)
                                    <input class="form-check-input" type="checkbox" checked disabled name="eventroundid" value="{{$round->eventroundid}}" >
                                    <label class="form-check-label" style="margin-left: 10px" >{!! $round->name!!}</label><br>
                                @endforeach
                                </div>

                            @else
                                <label for="Rounds" class="col-md-4 control-label">Rounds</label>
                                <div class="col-md-6">
                                @foreach ($eventrounds as $round)
                                    <input class="form-check-input" type="checkbox" name="eventroundid[]" value="{{$round->eventroundid}}" <?= (in_array($round->eventroundid, $usereventrounds)) ? 'checked' : '' ?> >
                                    <label class="form-check-label" style="margin-left: 10px" >{!! date('d F', strtotime($round->date)) . " - " .$round->name!!}</label><br>
                                @endforeach
                                </div>
                            @endif


                        </div>


                        <div class="form-group{{ $errors->has('membershipcode') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">{{$organisationname}} Membership Code</label>

                            <div class="col-md-6">
                                <input id="membershipcode" type="text" class="form-control" name="membershipcode" value="{{ $eventregistration->first()->membershipcode }}" >

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
                                <input id="phone" type="text" class="form-control" name="phone" value="{!! $eventregistration->first()->phone ?? old('phone') !!}" >

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
                                <textarea rows="3" id="address" type="text" class="form-control" name="address" >{!! $eventregistration->first()->address ?? old('address') !!}</textarea>
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
                                <textarea rows="5" id="address" type="text" class="form-control" name="notes" >{!! $eventregistration->first()->notes ?? old('notes') !!}</textarea>
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
                                <button type="submit" class="btn btn-warning" value="create" name="submit">
                                    Update
                                </button>

                                <button type="submit" class="btn btn-danger pull-right" value="remove" name="submit" id="deleteBtn">
                                    Remove Entry
                                </button>
                            </div>
                        </div>






                        {{--@foreach ($eventrounds as $eventround)--}}

                            {{--<div class="form-group">--}}
                                {{--<a href="{{route('updateeventroundview', $eventround->eventroundid)}}">--}}
                                    {{--<label for="eventround" class="col-md-4 control-label">{{$eventround->name}}</label>--}}
                                {{--</a>--}}
                                {{--<div class="col-md-4">--}}
                                    {{--<input type="text" class="form-control" name="cost" disabled placeholder="{{$eventround->name ?? ''}}">Round--}}
                                {{--</div>--}}

                                {{--<div class="col-md-2">--}}
                                    {{--<input type="text" class="form-control" name="cost" disabled placeholder="{{ '105 Entries' ?? ''}}">User count--}}
                                {{--</div>--}}
                            {{--</div>--}}

                            {{--<hr>--}}
                        {{--@endforeach--}}

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection


