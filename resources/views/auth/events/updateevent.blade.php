@section ('dashboard')
    <h1></h1>
@endsection

@include('layouts.title', ['title'=>'Update Event'])

@extends ('home')

@section ('content')
    {{--    {!! dd($event->first()->startdate) !!}--}}
    {{-- <div class="container"> --}}
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Update Event
                    <a href="{{route('events')}}">
                        <button type="submit" class="btn btn-default pull-right" id="addevent">
                            <i class="fa fa-backward" >  Back</i>
                        </button>
                    </a>
                </div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('updateevent', $event->first()->eventid) }}" id="eventformupdate">
                        {{ csrf_field() }}
                        <input type="hidden" name="eventid" value="{{ $event->first()->eventid }}">

                        <div class="form-group">
                            <label for="datetime" class="col-md-4 control-label">Dates:</label>

                            <div class="col-md-6">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" name="datetime" class="form-control pull-right" id="reservation" required autofocus>
                                </div>
                            </div>
                        </div>


                        <div class="form-group {{ $errors->has('eventerror') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Entries Close:</label>

                            <div class="col-md-6">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" name="closeentry" class="form-control pull-right" id="closeentry" >
                                </div>
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('eventerror') ? ' has-error' : '' }}" id="eventtype">
                            <label for="eventtype" class="col-md-4 control-label">Type</label>

                            <div class="col-md-6">
                                <input type="hidden" id="eventtypevalue" value="{{ $event->first()->eventtype }}">

                                <select name="eventtype" class="form-control" id="eventtypeid">
                                    <option value="0">Single Event</option>
                                    <option value="1">Weekly League</option>
                                </select>
                                @if ($errors->has('eventerror'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('eventerror') }}</strong>
                                    </span>
                                @endif
                            </div>

                        </div>


                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') ?? $event->first()->name }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group" id="status">
                            <label for="event" class="col-md-4 control-label">Status</label>

                            <div class="col-md-6">
                                <input type="text" hidden id="eventstatus" value="{{ old('status') ?? $event->first()->status }}">

                                <select name="status" class="form-control" id="eventstatusselect">
                                    <option value="open" selected>Open</option>
                                    <option value="entries-closed" >Entries Closed</option>
                                    <option value="completed">Completed</option>
                                    <option value="waitlist">Wait Listed</option>
                                    <option value="pending">Pending</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
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

                        <div class="form-group">
                            <div class="checkbox">
                                <label class="col-md-4 control-label">Visible</label>
                                <div class="col-md-6">
                                    @if (!empty($event))
                                        <?php
                                        $status='';
                                        if ($event->first()->visible == 1) {
                                            $status = 'checked';
                                        }
                                        ?>
                                        <input type="checkbox" name="visible" {{$status}}>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary" id="savebutton" value="save" name="submit">
                                    Save
                                </button>
                                <button type="submit" class="btn btn-success" id="savebutton" value="createeventround" name="submit">
                                    Add Event Session
                                </button>
                                <a href="{{ route('deleteevent', $event->first()->eventid) }}" class="btn btn-danger pull-right" role="button" id="deleteBtn">
                                    Delete
                                </a>
                            </div>
                        </div>


                        <hr>


                        @foreach ($eventrounds as $eventround)

                            <div class="form-group">
                                <a href="{{route('updateeventroundview', $eventround->eventroundid)}}">
                                    <label for="eventround" class="col-md-4 control-label">{{$eventround->name}}</label>
                                </a>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="cost" disabled placeholder="{{$eventround->name ?? ''}}">Round
                                </div>

                                <div class="col-md-2">
                                    <input type="text" class="form-control" name="cost" disabled placeholder="{{ '105 Entries' ?? ''}}">User count
                                </div>
                            </div>

                            <hr>
                        @endforeach




                        {{--<label for="schedule" class="col-md-4 control-label">Event Days</label><br>--}}

                        {{--<hr>--}}




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
                locale: {
                    format: 'DD/MM/YYYY'
                },
                "startDate": "<?php echo date('d/m/Y', strtotime($event->first()->startdate)) ?>",
                "endDate": "<?php echo date('d/m/Y', strtotime($event->first()->enddate)) ?>",
                autoclose : true
            });

            $('#closeentry').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true
            }).datepicker("update", "<?php echo date('d/m/Y', strtotime($event->first()->closeentry)) ?>");


        });

    </script>

@endsection


