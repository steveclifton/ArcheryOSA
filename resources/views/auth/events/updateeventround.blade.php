@section ('dashboard')
    <h1></h1>
@endsection

@extends ('home')

@section ('title')Update Event Round @endsection

@section ('content')
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            @include('includes.session_errors')
        </div>
    </div>

    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Update Event Round
                    <a href="{{route('updateeventview', $eventround->first()->eventid)}}">
                    <button type="submit" class="btn btn-default pull-right" id="addevent">
                        <i class="fa fa-backward" > Back</i>
                    </button>
                    </a>
                </div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('updateeventround', $eventround->first()->eventroundid ) }}" id="eventdayform">
                        {{ csrf_field() }}

                        <input type="hidden" name="eventid" value="{{$eventround->first()->eventid}}" >


                        <div class="form-group" id="date">
                            <label for="event" class="col-md-4 control-label">Date</label>

                            <div class="col-md-6">

                                <input type="text" hidden id="dateeventroundval" value="{{ $eventround->first()->date }}">

                                <select name="date" class="form-control" id="dateselect" required>

                                    @if($event->eventtype == 1)

                                    <option @if (old('date') === 'daily') {!! 'selected' !!} @endif value="daily">Daily</option>
                                    <option @if (old('date') === 'weekly') {!! 'selected' !!} @endif value="weekly">Weekly</option>

                                    @endif

                                    @foreach ($daterange->getDateRange() as $date)
                                        <option value="{{$date}}">{{date('d F Y', strtotime($date))}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="form-group{{ $errors->has('location') ? ' has-error' : '' }}">
                            <label for="location" class="col-md-4 control-label">Location</label>

                            <div class="col-md-6">
                                <input id="location" type="text" class="form-control" name="location" value="{{ old('location') ?? $eventround->first()->location }}">

                                @if ($errors->has('location'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('location') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>



                        <div class="form-group" id="roundelement">
                            <label for="event" class="col-md-4 control-label">Round</label>

                            <div class="col-md-6">
                                <input type="text" hidden id="roundsvalue" value="{{$eventround->first()->roundid}}">

                                <select name="roundid" class="form-control" id="roundselect">
                                    <option value="0">None</option>
                                    @foreach ($rounds as $round)
                                        <option value="{{$round->roundid}}">{{$round->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>





                        <div class="form-group{{ $errors->has('schedule') ? ' has-error' : '' }}">
                            <label for="schedule" class="col-md-4 control-label">Schedule</label>

                            <div class="col-md-6">
                                <textarea rows="5" id="schedule" type="text" class="form-control" name="schedule" >{{ old('schedule') ?? $eventround->first()->schedule }}</textarea>
                                @if ($errors->has('schedule'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('schedule') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        {{--Show those people who are enetered for this event day--}}


                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">

                                <button type="submit" class="btn btn-primary" id="savebutton" value="save" name="submit">
                                    Save
                                </button>
                                <a href="{!! route('deleteeventround', [$eventround->first()->eventroundid, urlencode($eventround->first()->name)]) !!}" class="btn btn-danger pull-right" role="button" id="deleteBtn">
                                    Delete
                                </a>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- </div> --}}



@endsection

