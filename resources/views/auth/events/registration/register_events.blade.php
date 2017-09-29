@section ('dashboard')
    <h1></h1>
@endsection

@include('layouts.title', ['title'=>'Event Registration'])

@extends ('home')

@section ('content')
        {{--{!! dd($event) !!}--}}
    {{-- <div class="container"> --}}
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Event Registration
                </div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('eventregistration', $event->first()->eventid) }}" id="eventformupdate">
                        {{ csrf_field() }}

                        - Select Division
                        - Name
                        - ANZ Number
                        - Checkbox for rounds to enter
                        - Show all event details in summary form


                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{Auth::user()->firstname }} {{ Auth::user()->lastname }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>






                        {{--@foreach ($eventrounds as $eventround)--}}

                            {{--<div class="form-group">--}}
                                {{--<a href="{{route('updateeventroundview', $eventround->eventroundid)}}">--}}
                                    {{--<label for="eventround" class="col-md-4 control-label">{{$eventround->name}}</label>--}}
                                {{--</a>--}}
                                {{--<div class="col-md-4">--}}
                                    {{--<input type="text" class="form-control" name="cost" disabled placeholder="{{$eventround->name ?? ''}}">Round--}}
                                {{--</div>--}}

                                {{--<div class="col-md-2">--}}
                                    {{--<input type="text" class="form-control" name="cost" disabled placeholder="{{ '105 Entries' ?? ''}}">User count--}}
                                {{--</div>--}}
                            {{--</div>--}}

                            {{--<hr>--}}
                        {{--@endforeach--}}

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection


