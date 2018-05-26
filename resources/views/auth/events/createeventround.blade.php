@section ('dashboard')
    <h1></h1>
@endsection

@extends ('home')

@section ('title')Create Event Round @endsection

@section ('content')

    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            @include('includes.session_errors')
        </div>
    </div>

    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Create Event Round
                    <a href="{{route('updateeventview', $eventid)}}">
                        <button type="submit" class="btn btn-default pull-right" id="addevent">
                            <i class="fa fa-backward" > Back</i>
                        </button>
                    </a>
                </div>


                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('createeventround') }}" id="eventdayform">
                        {{ csrf_field() }}

                        <input type="hidden" name="eventid" value="{{$eventid}}" >

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Event Round Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{old('name')}}" placeholder="Optional, replaces the round name">

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>



                        <div class="form-group {{ $errors->has('date') ? ' has-error' : '' }}" id="date">
                            <label for="event" class="col-md-4 control-label">Date</label>
                            <div class="col-md-6">

                                <select name="date" class="form-control" id="dateselect" required autofocus>
                                    <option value="">Please select option</option>

                                    @if($event->eventtype == 1)
                                        <option @if (old('date') === 'daily') selected @endif value="daily">Daily</option>
                                        <option @if (old('date') === 'weekly') selected @endif value="weekly">Weekly</option>
                                    @endif


                                    @foreach ($daterange->getDateRange() as $date)
                                        <option @if (old('date') === $date) selected @endif value="{{$date}}">{{date('d F Y', strtotime($date))}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if ($errors->has('date'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('date') }}</strong>
                                </span>
                            @endif
                        </div>



                        <div class="form-group{{ $errors->has('location') ? ' has-error' : '' }}">
                            <label for="location" class="col-md-4 control-label">Location</label>

                            <div class="col-md-6">
                                <input id="location" type="text" class="form-control" name="location" value="{{old('location')}}" placeholder="This is where the round will be shot">

                                @if ($errors->has('location'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('location') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>



                        <div class="form-group {{ $errors->has('roundid') ? ' has-error' : '' }}" id="roundelement">
                            <label for="event" class="col-md-4 control-label">Round</label>

                            <div class="col-md-6">

                                <select name="roundid" class="form-control" id="roundselect" required autofocus>
                                    <option value="">Select Round</option>
                                    @foreach ($rounds as $round)
                                        <option @if (old('roundid') == $round->roundid) selected @endif value="{{$round->roundid}}">{{$round->name}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('roundid'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('roundid') }}</strong>
                                    </span>
                                @endif

                            </div>

                        </div>




                        <div class="form-group{{ $errors->has('schedule') ? ' has-error' : '' }}">
                            <label for="schedule" class="col-md-4 control-label">Schedule</label>

                            <div class="col-md-6">
                                <textarea rows="5" id="schedule" type="text" class="form-control" name="schedule" placeholder="Optional"></textarea>
                                @if ($errors->has('schedule'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('schedule') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">

                                <button type="submit" class="btn btn-primary" id="savebutton" value="save" name="submit">
                                    Save
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

