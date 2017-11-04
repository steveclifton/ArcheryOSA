@section ('dashboard')
    <h1></h1>
@endsection

@extends ('home')

@section ('title')Update Event Round @endsection

@section ('content')
{{--    {!! dd($event->first()->startdate) !!}--}}
    {{-- <div class="container"> --}}
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


                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Event Round Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') ?? $eventround->first()->name }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group" id="date">
                            <label for="event" class="col-md-4 control-label">Date</label>

                            <div class="col-md-6">

                                <input type="text" hidden id="dateeventroundval" value="{{ $eventround->first()->date }}">

                                <select name="date" class="form-control" id="dateselect">

                                    <option @if (old('date') === 'daily') {!! 'selected' !!} @endif value="daily">Daily</option>
                                    <option @if (old('date') === 'weekly') {!! 'selected' !!} @endif value="weekly">Weekly</option>

                                    @foreach ($daterange->getDateRange() as $date)
                                        <option value="{{$date}}">{{date('d F Y', strtotime($date))}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="form-group{{ $errors->has('location') ? ' has-error' : '' }}">
                            <label for="location" class="col-md-4 control-label">Location</label>

                            <div class="col-md-6">
                                <input id="location" type="text" class="form-control" name="location" value="{{ old('location') ?? $eventround->first()->location }}" required autofocus>

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


                        <div class="form-group" id="organisationelement">
                            <label for="event" class="col-md-4 control-label">Parent Organisation</label>

                            <div class="col-md-6">
                                <input type="text" hidden id="organisationvalueeventround" value="{{$eventround->first()->organisationid}}">

                                <select name="organisationid" class="form-control" id="organisationselecteventround">
                                    <option value="0">None</option>
                                    @foreach ($organisations as $organisation)
                                        <option value="{{$organisation->organisationid}}">{{$organisation->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="form-group" id="divisionelement">
                            <label for="event" class="col-md-4 control-label">Select Divisions</label>

                            <div class="col-md-6">
                                <div style="overflow-y:scroll; height:200px; margin-bottom:10px;" id="divisionselect">

                                    <label class="form-check-label" style="margin-left: 10px" data-orgid="0">
                                        <input class="form-check-input" type="checkbox" name="divisions[]" value="0" >
                                        Open
                                    </label><br>
                                    @foreach ($divisions as $division)
                                        <label class="form-check-label" style="margin-left: 10px" data-orgid="{{$division->organisationid}}">
                                            <?php
                                                $checked = '';
                                                if (!empty($eventround->first()->divisions)) {
                                                    if (in_array($division->divisionid, unserialize($eventround->first()->divisions))) {
                                                        $checked = 'checked';
                                                    }
                                                }
                                            ?>


                                            <input class="form-check-input" type="checkbox" {{$checked}} name="divisions[]" value="{{$division->divisionid}}" >
                                            {{$division->name}}
                                        </label><br>
                                    @endforeach
                                </div>
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

