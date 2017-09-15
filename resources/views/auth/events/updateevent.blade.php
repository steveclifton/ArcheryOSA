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
                <div class="panel-heading">Update Event</div>
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('updateevent', $event->first()->eventid) }}" id="eventformupdate">
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

                        <label for="schedule" class="col-md-4 control-label">Event Days</label><br>
                        @foreach ($eventdays as $eventday)

                            <div class="form-group">
                                <hr>
                                <a href="{{route('updatedayevent', $eventday->eventdayid)}}">
                                    <label for="eventday" class="col-md-4 control-label">{{$eventday->name}}</label>

                                    <div class="col-md-4">
                                        <input type="text" class="form-control" name="cost" disabled placeholder="{{$eventday->name ?? ''}}"> {{--Update to be event round --}}
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" class="form-control" name="cost" disabled placeholder="{{ '105 Entries' ?? ''}}">{{--Update to be user count --}}
                                    </div>

                                </a>
                            </div>
                        @endforeach
                        <hr>

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
                            </div>
                        </div>


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
                "endDate": "<?php echo date('d/m/Y', strtotime($event->first()->enddate)) ?>"
            });

        });

    </script>

@endsection

