@section ('dashboard')
    <h1></h1>
@endsection

@extends ('home')

@section ('title')Create Event @endsection

@section ('content')

    {{-- <div class="container"> --}}
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Create New Event
                    <a href="{{route('events')}}">
                        <button type="submit" class="btn btn-default pull-right" id="addevent">
                            <i class="fa fa-backward" > Back</i>
                        </button>
                    </a>
                </div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('createevent') }}" id="eventform" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="form-group {{ $errors->has('eventerror') ? ' has-error' : '' }}">
                            <label for="datetime" class="col-md-4 control-label">Dates*</label>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>

                                    <input type="text" name="datetime" class="form-control pull-right" id="reservation" required>
                                </div>
                                @if ($errors->has('datetime'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('datetime') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('closeentry') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Entries Close</label>

                            <div class="col-md-6">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" name="closeentry" class="form-control pull-right" id="closeentry" >
                                </div>
                                @if ($errors->has('closeentry'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('closeentry') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group {{ $errors->has('eventtype') ? ' has-error' : '' }}" id="eventtype">
                            <label for="eventtype" class="col-md-4 control-label">Type</label>
                            <input type="hidden" id="eventtypevalue" value="0">

                            <div class="col-md-6">
                                <select name="eventtype" class="form-control" id="eventtypeid">
                                    <option value="0" selected>Single Event</option>
                                    <option value="1">Weekly League</option>
                                </select>
                                @if ($errors->has('eventtype'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('eventtype') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>



                        <div class="form-group {{ $errors->has('maxweeklyscores') ? ' has-error' : '' }}" id="maxweeklyinput">
                            <label for="maxweeklyscores" class="col-md-4 control-label">Max Scores Per Week*</label>

                            <div class="col-md-6">
                                <input id="maxweeklyvalue" type="text" class="form-control" name="maxweeklyscores">

                                @if ($errors->has('maxweeklyscores'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('maxweeklyscores') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('organisation') ? ' has-error' : '' }}" id="organisation">
                            <label for="organisation" class="col-md-4 control-label">Organisation</label>

                            <div class="col-md-6">
                                <select name="organisationid" class="form-control" id="organisationselecteventround">
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


                        <div class="form-group {{ $errors->has('divisions') ? ' has-error' : '' }}" id="">
                            <label for="event" class="col-md-4 control-label">Select Divisions</label>

                            <div class="col-md-6">
                                <div style="overflow-y:scroll; height:200px; margin-bottom:10px;" id="divisionselectcreate">

                                    <label class="form-check-label" style="margin-left: 10px" data-orgid="0">
                                        @if (empty($divisions))
                                            <input class="form-check-input" type="checkbox" name="divisions[]" value="0">Open
                                        @endif
                                    </label><br>
                                    @foreach ($divisions as $division)
                                        <label class="form-check-label" style="margin-left: 10px" data-orgid="{{$division->organisationid}}">
                                            <input class="form-check-input" type="checkbox" name="divisions[]" value="{{$division->divisionid}}" >
                                            {{$division->name}}
                                        </label><br>
                                    @endforeach
                                </div>

                                @if ($errors->has('divisions'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('divisions') }}</strong>
                                    </span>
                                @endif

                            </div>
                        </div>


                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Name*</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

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

                                <select name="status" class="form-control" id="eventstatus">
                                    <option value="open" selected>Open</option>
                                    <option value="in-progress">In Progress</option>
                                    <option value="entries-closed" >Entries Closed</option>
                                    <option value="completed">Completed</option>
                                    <option value="waitlist">Wait Listed</option>
                                    <option value="pending">Pending</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>
                        </div>


                        <div class="form-group{{ $errors->has('hostclub') ? ' has-error' : '' }}">
                            <label for="hostclub" class="col-md-4 control-label">Host Club*</label>

                            <div class="col-md-6">
                                <input id="hostclub" type="text" class="form-control" name="hostclub" value="{{ old('hostclub') }}" required autofocus>

                                @if ($errors->has('hostclub'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('hostclub') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group{{ $errors->has('location') ? ' has-error' : '' }}">
                            <label for="location" class="col-md-4 control-label">Location*</label>

                            <div class="col-md-6">
                                <input id="location" type="text" class="form-control" name="location" value="{{ old('location') }}" required autofocus>

                                @if ($errors->has('location'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('location') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group{{ $errors->has('contact') ? ' has-error' : '' }}">
                            <label for="contact" class="col-md-4 control-label">Contact Person*</label>

                            <div class="col-md-6">
                                <input id="contact" type="text" class="form-control" name="contact" value="{{ old('contact') }}" required autofocus>

                                @if ($errors->has('contact'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('contact') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">Contact E-Mail*</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group{{ $errors->has('cost') ? ' has-error' : '' }}">
                            <label for="cost" class="col-md-4 control-label">Cost*</label>

                            <div class="col-md-6">
                                <input id="cost" type="text" class="form-control" name="cost" value="{{ old('cost') }}" required autofocus>

                                @if ($errors->has('cost'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('cost') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('bankaccount') ? ' has-error' : '' }}">
                            <label for="bankaccount" class="col-md-4 control-label">Bank Account*</label>
                            <div class="col-md-6">
                                <input id="bankaccount" type="text" class="form-control" name="bankaccount" value="{{ old('bankaccount') }}" required autofocus>
                                @if ($errors->has('bankaccount'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('bankaccount') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('bankreference') ? ' has-error' : '' }}">
                            <label for="bankreference" class="col-md-4 control-label">Bank Account*</label>
                            <div class="col-md-6">
                                <input id="bankreference" type="text" class="form-control" name="bankreference" value="{{ old('bankreference') }}" required autofocus>
                                @if ($errors->has('bankreference'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('bankreference') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group{{ $errors->has('information') ? ' has-error' : '' }}">
                            <label for="information" class="col-md-4 control-label">Event Info</label>
                            <div class="col-md-6">
                                <textarea rows="5" id="information" type="text" class="form-control" name="information" >{{ old('information') }}</textarea>
                                @if ($errors->has('information'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('information') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>




                        <div class="form-group{{ $errors->has('schedule') ? ' has-error' : '' }}">
                            <label for="schedule" class="col-md-4 control-label">Schedule</label>
                            <div class="col-md-6">
                                <textarea rows="5" id="schedule" type="text" class="form-control" name="schedule" >{{ old('schedule') }}</textarea>
                                @if ($errors->has('schedule'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('schedule') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <hr>
                            <h3>Setup</h3>
                            {{--<div class="form-group{{ $errors->has('multipledivisions') ? ' has-error' : '' }}">--}}
                                {{--<label for="bankaccount" class="col-md-4 control-label">Multiple Division Entries</label>--}}
                                {{--<div class="col-md-6">--}}
                                    {{--<input type="checkbox"  name="multipledivisions" style="margin-top: 10px">--}}
                                    {{--@if ($errors->has('multipledivisions'))--}}
                                        {{--<span class="help-block">--}}
                                            {{--<strong>{{ $errors->first('multipledivisions') }}</strong>--}}
                                        {{--</span>--}}
                                    {{--@endif--}}
                                {{--</div>--}}
                            {{--</div>--}}

                            <div class="form-group">
                                <label class="col-md-4 control-label">Visible</label>
                                <div class="col-md-6">
                                    <input type="checkbox" name="visible" style="margin-top: 10px">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">Scoring Enabled</label>
                                <div class="col-md-6">
                                    <input type="checkbox" name="scoringenabled" style="margin-top: 10px">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">Users Can Score</label>
                                <div class="col-md-6">
                                    <input type="checkbox" name="userscanscore" style="margin-top: 10px">
                                </div>
                            </div>



                        <hr>
                            <h3>Sponsorship</h3>
                            <div class="form-group">
                                <label class="col-md-4 control-label">Sponsored Event</label>
                                <div class="col-md-6">
                                    <input type="checkbox" name="sponsored" style="margin-top: 10px">
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('dtimage') ? ' has-error' : '' }}">
                                <label for="desktopimage" class="col-md-4 control-label">Desktop Image (1000x400px)</label>

                                <div class="col-md-6">
                                    <input type="file" class="form-control" name="dtimage">

                                    @if ($errors->has('dtimage'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('dtimage') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('dtimage') ? ' has-error' : '' }}">
                                <label for="mobimage" class="col-md-4 control-label">Mobile Image(800x500px)</label>

                                <div class="col-md-6">
                                    <input type="file" class="form-control" name="mobimage">

                                    @if ($errors->has('mobimage'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('mobimage') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('sponsorimageurl') ? ' has-error' : '' }}">
                                <label for="sponsorimageurl" class="col-md-4 control-label">Sponsor Image URL</label>

                                <div class="col-md-6">
                                    <input id="sponsorimageurl" type="text" class="form-control" name="sponsorimageurl" value="{{ old('sponsorimageurl')}}" >

                                    @if ($errors->has('sponsorimageurl'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('sponsorimageurl') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('sponsortext') ? ' has-error' : '' }}">
                                <label for="sponsortext" class="col-md-4 control-label">Sponsor Text</label>

                                <div class="col-md-6">
                                    <textarea rows="5" id="sponsortext" type="text" class="form-control" name="sponsortext" >{{ old('sponsortext') }}</textarea>
                                    @if ($errors->has('sponsortext'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('sponsortext') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        <hr>


                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary" id="createbutton" value="create" name="submit">
                                    Add Rounds
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

            //Date range picker with time picker
            $('#reservation').daterangepicker({
                locale: {
                    format: 'DD/MM/YYYY'
                },
            });

            $('#closeentry').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true
            }).datepicker("update");


        });

    </script>

@endsection

