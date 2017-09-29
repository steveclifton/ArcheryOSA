@section ('dashboard')
    <h1></h1>
@endsection

@include('layouts.title', ['title'=>'Create Event Round'])

@extends ('home')

@section ('content')
{{--    {!! dd($event->first()->startdate) !!}--}}
    {{-- <div class="container"> --}}
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
                                <input id="name" type="text" class="form-control" name="name"  required autofocus>

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

                                <select name="date" class="form-control" id="dateselect">
                                    @foreach ($daterange as $date)
                                        <option value="{{$date->format("d-m-Y")}}">{{$date->format("d-m-Y")}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>





                        <div class="form-group{{ $errors->has('location') ? ' has-error' : '' }}">
                            <label for="location" class="col-md-4 control-label">Location</label>

                            <div class="col-md-6">
                                <input id="location" type="text" class="form-control" name="location"  required autofocus>

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
                                <div style="overflow-y:scroll; height:200px; margin-bottom:10px;" id="divisionselectcreate">

                                    <label class="form-check-label" style="margin-left: 10px" data-orgid="0">
                                        <input class="form-check-input" type="checkbox" name="divisions[]" value="0" >
                                        Open
                                    </label><br>
                                    @foreach ($divisions as $division)
                                        <label class="form-check-label" style="margin-left: 10px" data-orgid="{{$division->organisationid}}">

                                            <input class="form-check-input" type="checkbox" name="divisions[]" value="{{$division->divisionid}}" >
                                            {{$division->name}}
                                        </label><br>
                                    @endforeach
                                </div>
                            </div>

                        </div>


                        <div class="form-group{{ $errors->has('schedule') ? ' has-error' : '' }}">
                            <label for="schedule" class="col-md-4 control-label">Schedule</label>

                            <div class="col-md-6">
                                <textarea rows="5" id="schedule" type="text" class="form-control" name="schedule" ></textarea>
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
    {{-- </div> --}}



@endsection

