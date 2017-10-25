@section ('dashboard')
    <h1></h1>
@endsection

@include('layouts.title', ['title'=>'Update Event'])

@extends ('home')

@section ('content')

    <div class="row">
        <div class="col-md-10 col-md-offset-1">
        @if(session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @elseif (session()->has('failure'))
            <div class="alert alert-danger">
                {{ session()->get('failure') }}
            </div>
        @endif
        </div>
    </div>

    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="row">
                <div class="col-md-12">
                    <div>
                        <div class="box box-info collapsed-box">
                            <div class="box-header with-border">
                                <h3 class="box-title" >Event Entries</h3>
                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse">Click to Open <i class="fa fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="box-body">
                                <div class="table-responsive">
                                    <form class="form-horizontal" method="POST" action="{{route('updateregistrationstatus', $event->first()->eventid)}}" id="eventformupdate">
                                        {{ csrf_field() }}

                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>Archers Name</th>
                                                <th>Club</th>
                                                <th>Division</th>
                                                <th>Status</th>
                                                <th>Paid</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($users as $user)
                                                @php
                                                    switch ($user->entrystatusid) {
                                                        case 1 :
                                                            $colour = '#FAD9AE';
                                                        break;

                                                        case 2 :
                                                            $colour = '#CDFAAE';
                                                        break;

                                                        case 3 :
                                                            $colour = '#AEDAFA';
                                                        break;

                                                        case 4 :
                                                            $colour = '#FAAEAE';
                                                        break;
                                                    }

                                                @endphp

                                                <tr onmouseover="this.style.backgroundColor='lightgrey'" onmouseout="this.style.backgroundColor='{{$colour}}'" style="background: {{$colour}}">
                                                    <input type="hidden" name="userid[]" value="{{$user->userid}}">
                                                    <td>{{$user->fullname}}</td>
                                                    <td>{{$user->club}}</td>
                                                    <td>{{$user->division}}</td>
                                                    <td>
                                                        <select name="userstatus[]" id="userstatusselect">
                                                            @foreach($entrystatus as $status)
                                                                <option value="{{$status->entrystatusid}}" <?= ($user->entrystatusid == $status->entrystatusid) ? 'selected' : '' ?>>
                                                                    {{$status->name}}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="userpaid[]" id="userpaidselect">
                                                            <option value="0" <?= ($user->paid == 0) ? 'selected' : '' ?>>No</option>
                                                            <option value="1" <?= ($user->paid == 1) ? 'selected' : '' ?>>Yes</option>
                                                            <option value="2" <?= ($user->paid == 2) ? 'selected' : '' ?>>N/A</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>

                                        <button type="submit" class="btn btn-success pull-right" value="update" name="update">
                                            Update
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading"> Update Event
                    <a href="{{route('events')}}">
                        <button type="submit" class="btn btn-default pull-right" id="addevent">
                            <i class="fa fa-backward" >  Back</i>
                        </button>
                    </a>
                </div>

                <div class="panel-body">
                    <h3 style="text-align: center;font-weight: bold;">{{$event->first()->name}}</h3><br>
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
                                    <input type="text" name="datetime" class="form-control pull-right" id="reservation" required >
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

                        <div class="form-group {{ $errors->has('organisation') ? ' has-error' : '' }}" id="organisation">
                            <label for="organisation" class="col-md-4 control-label">Organisation</label>

                            <div class="col-md-6">
                                <input type="hidden" id="organisationidvalue" value="{{ $event->first()->organisationid }}">

                                <select name="organisationid" class="form-control" id="organisationselect">
                                    <option value="0" selected>None</option>
                                    @foreach ($organisations as $organisation)
                                        <option value="{{$organisation->organisationid}}">{{$organisation->name}}</option>
                                    @endforeach

                                </select>
                                @if ($errors->has('organisation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('organisation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') ?? $event->first()->name }}" required >

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
                                <input id="hostclub" type="text" class="form-control" name="hostclub" value="{{ old('hostclub') ?? $event->first()->hostclub }}" required >

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
                                <input id="location" type="text" class="form-control" name="location" value="{{ old('location') ?? $event->first()->location }}" required >

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
                                <input id="contact" type="text" class="form-control" name="contact" value="{{ old('contact') ?? $event->first()->contact }}" required >

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
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') ?? $event->first()->email }}" required >

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
                                <input id="cost" type="text" class="form-control" name="cost" value="{{ old('cost') ?? $event->first()->cost }}" required >

                                @if ($errors->has('cost'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('cost') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('bankaccount') ? ' has-error' : '' }}">
                            <label for="bankaccount" class="col-md-4 control-label">Bank Account</label>

                            <div class="col-md-6">
                                <input id="bankaccount" type="text" class="form-control" name="bankaccount" value="{{ old('bankaccount') ?? $event->first()->bankaccount }}" >

                                @if ($errors->has('bankaccount'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('bankaccount') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('multipledivisions') ? ' has-error' : '' }}">
                            <label for="bankaccount" class="col-md-4 control-label">Allow Multiple Division Entries</label>
                            <div class="col-md-6">

                                @if (!empty($event))
                                    <?php
                                        $multipledivisions='';
                                        if ($event->first()->multipledivisions == 1) {
                                            $multipledivisions = 'checked';
                                        }
                                    ?>
                                    <input style="margin-top: 10px" type="checkbox" name="multipledivisions" {{$multipledivisions}}>
                                @endif

                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-md-4 control-label">Visible</label>
                            <div class="col-md-6">
                                @if (!empty($event))
                                    <?php
                                    $status='';
                                    if ($event->first()->visible == 1) {
                                        $status = 'checked';
                                    }
                                    ?>
                                    <input style="margin-top: 10px" type="checkbox" name="visible" {{$status}}>
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




                        <hr>


                        @if(!$eventrounds->isEmpty())
                            <h3>Event Rounds</h3>
                        @endif

                        @foreach ($eventrounds as $eventround)

                            <div class="form-group">
                                <a href="{{route('updateeventroundview', $eventround->eventroundid)}}">
                                    <label for="eventround" class="col-md-4 control-label">{{$eventround->name}}</label>
                                </a>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" name="cost" disabled placeholder="{{$eventround->name ?? ''}}">Round
                                </div>

                                <div class="col-md-3">
                                    <input type="text" class="form-control" name="cost" disabled placeholder="{!! ($eventround->date == 'weekly' || $eventround->date == 'daily') ? ucfirst($eventround->date) : date('d-m-Y', strtotime($eventround->date)) !!}">Date
                                </div>
                            </div>

                            <hr>
                        @endforeach

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-success" id="savebutton" value="save" name="submit">
                                    Save
                                </button>
                                <button type="submit" class="btn btn-primary" id="savebutton" value="createeventround" name="submit">
                                    Add Event Session
                                </button>
                                <a href="{!! route('deleteevent', [$event->first()->eventid, urlencode($event->first()->name)]) !!}" class="btn btn-danger pull-right" role="button" id="deleteBtn">
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

            if ("<?php echo date('d/m/Y', strtotime($event->first()->closeentry)) ?>" != "01/01/1970") {
                $('#closeentry').datepicker({
                    format: 'dd/mm/yyyy',
                    autoclose: true
                }).datepicker("update", "<?php echo date('d/m/Y', strtotime($event->first()->closeentry)) ?>");
            }

        });

    </script>

@endsection


