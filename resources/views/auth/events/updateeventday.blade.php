@section ('dashboard')
    <h1></h1>
@endsection

@include('layouts.title', ['title'=>'Update Event Day'])

@extends ('home')

@section ('content')
{{--    {!! dd($event->first()->startdate) !!}--}}
    {{-- <div class="container"> --}}
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Update Event Day</div>
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('updateeventday', $eventday->first()->eventdayid ) }}" id="eventdayform">
                        {{ csrf_field() }}


                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Event Day Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') ?? $eventday->first()->name }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group{{ $errors->has('location') ? ' has-error' : '' }}">
                            <label for="location" class="col-md-4 control-label">Location</label>

                            <div class="col-md-6">
                                <input id="location" type="text" class="form-control" name="location" value="{{ old('location') ?? $eventday->first()->location }}" required autofocus>

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
                                <input type="text" hidden id="roundsvalue" value="{{$eventday->first()->roundid}}">

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
                                <input type="text" hidden id="organisationvalueeventday" value="{{$eventday->first()->organisationid}}">

                                <select name="organisationid" class="form-control" id="organisationselecteventday">
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
                                                if (!empty($eventday->first()->divisions)) {
                                                    if (in_array($division->divisionid, unserialize($eventday->first()->divisions))) {
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
                                <textarea rows="5" id="schedule" type="text" class="form-control" name="schedule" >{{ old('schedule') ?? $eventday->first()->schedule }}</textarea>
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
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- </div> --}}



@endsection

