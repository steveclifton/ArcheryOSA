@section ('dashboard')
    <h1></h1>
@endsection

@extends ('home')

@section ('title')Event Registration @endsection

@section ('content')
        {{--{!! dd($event) !!}--}}
    {{-- <div class="container"> --}}

    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            @include('includes.session_errors')
        </div>
    </div>

    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Event Registration
                    <a href="{{route('updateeventview', $event->eventid) }}">
                        <button type="submit" class="btn btn-default pull-right" id="addevent">
                            <i class="fa fa-backward" > Back</i>
                        </button>
                    </a>
                </div>



                <div class="panel-body">

                    <form class="form-horizontal" method="POST" action="{{ route('adminadduser',  $event->eventid ) }}" >
                        {{ csrf_field() }}

                        <input type="text" name="eventid" hidden value="{{$event->eventid}}">
                        <input type="text" name="enteredbyuserid" hidden value="{{ Auth::id() }}">
                        <input type="text" name="userid" hidden id="searchusersid" value="-1">

                        <meta name="csrf-token" content="{{ csrf_token() }}">
                        <div class="form-group">
                            <label for="name" class="col-md-4 control-label">Search User Email</label>

                            <div class="col-md-6">
                                <input type="text" id="searchuser" class="form-control" >
                                <span style="color: lightsteelblue">Keep typing the Archer's email address..</span>
                            </div>
                        </div>

                        <div class="form-group" id="searchuserresults" style="display: none">
                            <label for="" class="col-md-4 control-label">Archers</label>

                            <div class="col-md-6 foundusers"></div>
                        </div>



                        {{--Here you can choose to enrol others for the events--}}


                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name"  required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>



                        @if ($event->multipledivisions == 0)
                            <div class="form-group {{ $errors->has('division') ? ' has-error' : '' }}" id="organisation">
                                <label for="organisation" class="col-md-4 control-label">Division</label>

                                <div class="col-md-6">
                                    <select name="divisions" class="form-control" id="organisation" required>
                                        <option value="" disabled selected>Please select</option>

                                        @foreach ($divisions as $division)
                                            <option value="{{$division->divisionid}}">{{$division->name}}</option>
                                        @endforeach

                                    </select>
                                    @if ($errors->has('divisions'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('divisions') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="form-group {{ $errors->has('division') ? ' has-error' : '' }}" id="organisation">
                                <label for="organisation" class="col-md-4 control-label">Division</label>

                                <div class="col-md-6" style="overflow-y:scroll; height:200px; margin-bottom:10px;">

                                    @foreach ($divisions as $division)
                                        <input class="form-check-input" type="checkbox" name="divisions[]" value="{{$division->divisionid}}" >
                                        <label class="form-check-label" style="margin-left: 10px" >{{$division->name}}</label><br>
                                    @endforeach
                                </div>
                                @if ($errors->has('divisions'))
                                    <span class="help-block">
                                            <strong>{{ $errors->first('divisions') }}</strong>
                                        </span>
                                @endif
                            </div>
                        @endif

                        @if ($event->ignoregenders == 0)
                            <div class="form-group {{ $errors->has('division') ? ' has-error' : '' }}" id="gender">

                                    <label for="gender" class="col-md-4 control-label">Gender</label>
                                    <div class="col-md-6">
                                        <input class="form-check-input" type="radio" name="gender" value="M">
                                        <label class="form-check-label" style="margin-left: 10px">Mens</label><br>
                                        <input class="form-check-input" type="radio" name="gender" value="F">
                                        <label class="form-check-label" style="margin-left: 10px">Womens</label>
                                    </div>

                            </div>
                        @endif

                        <div class="form-group {{ $errors->has('division') ? ' has-error' : '' }}" id="Rounds">

                            @if ($event->eventtype == 1)
                                <label for="Rounds" class="col-md-4 control-label">Round</label>
                                <div class="col-md-6">
                                    @foreach ($eventround as $round)
                                        <input class="form-check-input" type="checkbox" checked disabled name="eventroundid" value="{{$round->eventroundid}}" >
                                        <label class="form-check-label" style="margin-left: 10px">{!! $round->name !!}</label>
                                    @endforeach
                                    @if ($errors->has('eventroundid'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('eventroundid') }}</strong>
                                        </span>
                                    @endif
                                </div>

                            @else
                                <label for="Rounds" class="col-md-4 control-label">Rounds</label>
                                <div class="col-md-6">
                                    @foreach ($eventround as $round)
                                        <input class="form-check-input" type="checkbox" name="eventroundid[]" value="{{$round->eventroundid}}" >
                                        <label class="form-check-label" style="margin-left: 10px" >{!! date('d F', strtotime($round->date)) . " - " .$round->name!!}</label><br>
                                    @endforeach
                                    @if ($errors->has('eventroundid'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('eventroundid') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            @endif


                        </div>





                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary" value="create" name="submit">
                                    Enter
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection


