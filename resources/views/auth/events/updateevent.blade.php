@section ('dashboard')
    <h1></h1>
@endsection

@include('layouts.title', ['title'=>'Create Event'])

@extends ('home')

@section ('content')
{{--    {!! dd($event->first()->startdate) !!}--}}
    {{-- <div class="container"> --}}
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Create New Event</div>
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('createevent') }}" id="eventform">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="datetime" class="col-md-4 control-label">Date range:</label>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" name="datetime" class="form-control pull-right" id="reservation" required autofocus>
                                </div>
                            </div>
                        </div>


                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Event Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') ?? $event->first()->name }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group{{ $errors->has('hostclub') ? ' has-error' : '' }}">
                            <label for="hostclub" class="col-md-4 control-label">Host Club</label>

                            <div class="col-md-6">
                                <input id="hostclub" type="text" class="form-control" name="hostclub" value="{{ old('hostclub') ?? $event->first()->hostclub }}" required autofocus>

                                @if ($errors->has('hostclub'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('hostclub') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group{{ $errors->has('location') ? ' has-error' : '' }}">
                            <label for="location" class="col-md-4 control-label">Location</label>

                            <div class="col-md-6">
                                <input id="location" type="text" class="form-control" name="location" value="{{ old('location') ?? $event->first()->location }}" required autofocus>

                                @if ($errors->has('location'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('location') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group{{ $errors->has('contact') ? ' has-error' : '' }}">
                            <label for="contact" class="col-md-4 control-label">Contact Person</label>

                            <div class="col-md-6">
                                <input id="contact" type="text" class="form-control" name="contact" value="{{ old('contact') ?? $event->first()->contact }}" required autofocus>

                                @if ($errors->has('contact'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('contact') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">Contact E-Mail</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') ?? $event->first()->email }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group{{ $errors->has('cost') ? ' has-error' : '' }}">
                            <label for="cost" class="col-md-4 control-label">Cost</label>

                            <div class="col-md-6">
                                <input id="cost" type="text" class="form-control" name="cost" value="{{ old('cost') ?? $event->first()->cost }}" required autofocus>

                                @if ($errors->has('cost'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('cost') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>



                        <div class="form-group{{ $errors->has('schedule') ? ' has-error' : '' }}">
                            <label for="schedule" class="col-md-4 control-label">Schedule</label>

                            <div class="col-md-6">
                                <textarea rows="5" id="schedule" type="text" class="form-control" name="schedule" >{{ old('schedule') ?? $event->first()->schedule }}</textarea>
                                @if ($errors->has('schedule'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('schedule') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        @for ($i = 0; $i < $event->first()->daycount; $i++)

                            <div id="dailydetails">
                                <hr>
                                <h3>Day {{$i + 1}}</h3>
                                <div class="form-group" id="roundelement">
                                    <label for="event" class="col-md-4 control-label">Round</label>

                                    <div class="col-md-6">

                                        <select name="roundid" class="form-control" id="roundselect">
                                            <option value="0">None</option>
                                            @foreach ($rounds as $round)
                                                <option value="{{$round->organisationid}}">{{$round->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                                <div class="form-group" id="organisationelement">
                                    <label for="event" class="col-md-4 control-label">Parent Organisation</label>

                                    <div class="col-md-6">

                                        <select name="parentorganisationid" class="form-control" id="organisationselect">
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
                                                <input class="form-check-input" type="checkbox" name="divisions[]" value="null" >
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
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">

                                    <button type="submit" class="btn btn-primary" id="savebutton" value="save" name="submit">
                                        Save
                                    </button>
                                </div>
                            </div>
                        @endfor


                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- </div> --}}
    <!-- daterangepicker -->
    <script src="{{URL::asset('bower_components/moment/min/moment.min.js')}}"></script>
    <script src="{{URL::asset('bower_components/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
    <!-- datepicker -->
    <script src="{{URL::asset('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
    <script>
        $(function () {
            //Initialize Select2 Elements
            $('.select2').select2();

            //Date range picker
            $('#reservation').daterangepicker({
                "startDate": "<?php echo $event->first()->startdate ?>",
                "endDate": "<?php echo $event->first()->enddate ?>"
            });
            //Date range picker with time picker
            $('#reservationtime').daterangepicker({
                format: 'DD/MM/YYYY'
            });
        });

    </script>

@endsection

