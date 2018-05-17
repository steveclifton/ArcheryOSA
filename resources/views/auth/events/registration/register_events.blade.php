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
                    <a href="{{route('eventdetails', ['eventurl' => $event->url]) }}">
                        <button type="submit" class="btn btn-default pull-right" id="addevent">
                            <i class="fa fa-backward" > Back</i>
                        </button>
                    </a>
                </div>

                <div class="panel-body">

                    <form class="form-horizontal">
                        <meta name="csrf-token" content="{{ csrf_token() }}">

                        <div class="form-group {{ $errors->has('clubid') ? ' has-error' : '' }}" id="club">
                            <label for="selectarcher" class="col-md-4 control-label">Archers</label>
                            <div class="col-md-6">
                                <select class="form-control" id="selectarcher" >
                                    <option value="{{Auth::id()}}">{{ucwords(Auth::user()->firstname) . " " . ucwords(Auth::user()->lastname)}}</option>

                                    @foreach ($relations as $relation)
                                        <option value="{{$relation->userid}}">{{ucwords($relation->firstname) . ' ' . ucwords($relation->lastname)}}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                    </form>

                    <form class="form-horizontal" method="POST" action="{{ route('eventregistration', ['eventurl' => $event->url]) }}" id="eventformupdate">

                        <input type="text" name="eventid" hidden value="{{$event->eventid}}">

                        <div id="formdata">
                            @include('includes.forms.entryform')
                        </div>

                        <div class="form-group{{ $errors->has('cost') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Cost</label>

                            <div class="col-md-6">
                                <input id="cost" type="text" class="form-control" name="cost" value="${{$event->cost}}" disabled>

                                @if ($errors->has('cost'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('cost') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('bankaccount') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Events Bank Account</label>

                            <div class="col-md-6">
                                <input id="bankaccount" type="text" class="form-control" name="bankaccount" value="{{$event->bankaccount}}" disabled>

                                @if ($errors->has('bankaccount'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('bankaccount') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group enterbutton {{empty($archerentry->eventregistration) === true ? '' : 'hidden'}}">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary" value="create" name="submit">
                                    Enter
                                </button>
                            </div>
                        </div>

                        <div class="form-group updateremovebutton {{empty($archerentry->eventregistration) === true ? 'hidden' : ''}}">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-warning" value="update" name="submit">
                                    Update
                                </button>

                                <button type="submit" class="btn btn-danger pull-right" value="remove" name="submit" id="deleteBtn">
                                    Remove Entry
                                </button>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

@endsection


