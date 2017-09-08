@section ('dashboard')
    <h1></h1>
@endsection

@if(!empty($round))
    @include('layouts.title', ['title'=>'Edit Round'])
@else
    @include('layouts.title', ['title'=>'Create Round'])
@endif


@extends ('home')

@section ('content')
    {{--{!! dd($round); !!}--}}
    {{-- <div class="container"> --}}
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    @if(!empty($round))
                        Update
                    @else
                        Create
                    @endif
                    New Round
                    <a href="{{route('rounds')}}">
                        <button type="submit" class="btn btn-default pull-right">
                            <i class="fa fa-backward" >  Back</i>
                        </button>
                    </a>
                </div>

                <div class="panel-body">

                    @if (!empty($round))
                        <form class="form-horizontal" method="POST" action="{{ route('updateround', urlencode($round->first()->name)) }}">
                            @else
                                <form class="form-horizontal" method="POST" action="{{ route('createround') }}">
                                    @endif
                                    {{ csrf_field() }}

                                    @if (!empty($round))
                                        <input type="text" name="roundid" hidden value="{{$round->first()->roundid}}">
                                    @endif


                                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                        <label for="event" class="col-md-4 control-label">Name</label>

                                        <div class="col-md-6">
                                            @if (!empty($round))
                                                <input type="text" class="form-control" name="name" value="{{ old('name', $round->first()->name) }}" required autofocus>
                                            @else
                                                <input type="text" class="form-control" name="name" required autofocus value="{{ old('name')}}">
                                            @endif

                                            @if ($errors->has('name'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
                                        <label for="event" class="col-md-4 control-label">Round Code</label>

                                        <div class="col-md-6">
                                            @if (!empty($round))
                                                <input type="text" class="form-control" name="code" value="{{ old('name', $round->first()->code) }}" required autofocus>
                                            @else
                                                <input type="text" class="form-control" name="code" required autofocus value="{{ old('code')}}">
                                            @endif

                                            @if ($errors->has('code'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('code') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                                        <label for="event" class="col-md-4 control-label">Description</label>

                                        <div class="col-md-6">
                                            @if (!empty($round))
                                                <textarea class="form-control" name="description" required autofocus >{{ old('description', $round->first()->description) }}</textarea>
                                            @else
                                                <textarea class="form-control" name="description" required autofocus >{{ old('description')}}</textarea>
                                            @endif

                                            @if ($errors->has('description'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('description') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('unit') ? ' has-error' : '' }}">
                                        <label for="event" class="col-md-4 control-label">Round Code</label>

                                        <div class="col-md-4">
                                            <select name="unit" class="form-control">
                                                <option value="m">Meters</option>
                                                <option value="y">Yards</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('dist1') ? ' has-error' : '' }}">
                                        <label for="event" class="col-md-4 control-label">Distance 1</label>

                                        <div class="col-md-2">
                                            @if (!empty($round))
                                                <input type="text" class="form-control" name="dist1" value="{{ old('dist1', $round->first()->dist1) }}"  required autofocus>
                                            @else
                                                <input type="text" class="form-control" name="dist1" value="{{ old('dist1')}}" required autofocus>
                                            @endif

                                            @if ($errors->has('dist1'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('dist1') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('dist1max') ? ' has-error' : '' }}">
                                        <label for="event" class="col-md-4 control-label">Distance 1 Max</label>

                                        <div class="col-md-2">
                                            @if (!empty($round))
                                                <input type="text" class="form-control" name="dist1max" value="{{ old('dist1max', $round->first()->dist1max) }}" required autofocus >
                                            @else
                                                <input type="text" class="form-control" name="dist1max" value="{{ old('dist1max')}}" required autofocus >
                                            @endif

                                            @if ($errors->has('dist1max'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('dist1max') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('dist2') ? ' has-error' : '' }}">
                                        <label for="event" class="col-md-4 control-label">Distance 2</label>

                                        <div class="col-md-2">
                                            @if (!empty($round))
                                                <input type="text" class="form-control" name="dist2" value="{{ old('dist2', $round->first()->dist2) }}" >
                                            @else
                                                <input type="text" class="form-control" name="dist2" value="{{ old('dist2')}}">
                                            @endif

                                            @if ($errors->has('dist2'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('dist2') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('dist2max') ? ' has-error' : '' }}">
                                        <label for="event" class="col-md-4 control-label">Distance 2 Max</label>

                                        <div class="col-md-2">
                                            @if (!empty($round))
                                                <input type="text" class="form-control" name="dist2max" value="{{ old('dist2max', $round->first()->dist2max) }}" >
                                            @else
                                                <input type="text" class="form-control" name="dist2max" value="{{ old('dist2max')}}">
                                            @endif

                                            @if ($errors->has('dist2max'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('dist2max') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('dist3') ? ' has-error' : '' }}">
                                        <label for="event" class="col-md-4 control-label">Distance 3</label>

                                        <div class="col-md-2">
                                            @if (!empty($round))
                                                <input type="text" class="form-control" name="dist3" value="{{ old('dist3', $round->first()->dist3) }}" >
                                            @else
                                                <input type="text" class="form-control" name="dist3" value="{{ old('dist3')}}">
                                            @endif

                                            @if ($errors->has('dist3'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('dist3') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('dist3max') ? ' has-error' : '' }}">
                                        <label for="event" class="col-md-4 control-label">Distance 3 Max</label>

                                        <div class="col-md-2">
                                            @if (!empty($round))
                                                <input type="text" class="form-control" name="dist3max" value="{{ old('dist3max', $round->first()->dist3max) }}" >
                                            @else
                                                <input type="text" class="form-control" name="dist3max" value="{{ old('dist3max')}}">
                                            @endif

                                            @if ($errors->has('dist3max'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('dist3max') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('dist4') ? ' has-error' : '' }}">
                                        <label for="event" class="col-md-4 control-label">Distance 4</label>

                                        <div class="col-md-2">
                                            @if (!empty($round))
                                                <input type="text" class="form-control" name="dist4" value="{{ old('dist4', $round->first()->dist4) }}" >
                                            @else
                                                <input type="text" class="form-control" name="dist4" value="{{ old('dist4')}}" >
                                            @endif

                                            @if ($errors->has('dist4'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('dist4') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('dist4max') ? ' has-error' : '' }}">
                                        <label for="event" class="col-md-4 control-label">Distance 4 Max</label>

                                        <div class="col-md-2">
                                            @if (!empty($round))
                                                <input type="text" class="form-control" name="dist4max" value="{{ old('dist4max', $round->first()->dist4max) }}" >
                                            @else
                                                <input type="text" class="form-control" name="dist4max" value="{{ old('dist4max')}}">
                                            @endif

                                            @if ($errors->has('dist4max'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('dist4max') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('totalmax') ? ' has-error' : '' }}">
                                        <label for="event" class="col-md-4 control-label">Max Total</label>

                                        <div class="col-md-2">
                                            @if (!empty($round))
                                                <input type="text" class="form-control" name="totalmax" value="{{ old('totalmax', $round->first()->totalmax) }}" >
                                            @else
                                                <input type="text" class="form-control" name="totalmax" value="{{ old('totalmax')}}">
                                            @endif

                                            @if ($errors->has('totalmax'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('totalmax') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>




                                    <div class="form-group">
                                        <div class="checkbox">
                                            <label class="col-md-4 control-label">Visible</label>
                                            <div class="col-md-6">
                                                @if (!empty($round))
                                                    <?php
                                                    $status='';
                                                    if ($round->first()->visible == 1) {
                                                        $status = 'checked';
                                                    }
                                                    ?>
                                                    <input type="checkbox" name="visible" {{$status}}>
                                                @else
                                                    <input type="checkbox" name="visible">
                                                @endif
                                            </div>
                                        </div>

                                    </div>


                                    <div class="form-group">
                                        <div class="col-md-6 col-md-offset-4">
                                            <button type="submit" class="btn btn-primary">
                                                @if (!empty($round))
                                                    Update
                                                @else
                                                    Create
                                                @endif
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

