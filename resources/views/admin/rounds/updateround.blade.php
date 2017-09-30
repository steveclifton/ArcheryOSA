@section ('dashboard')
    <h1></h1>
@endsection

@include('layouts.title', ['title'=>'Edit Round'])

@extends ('home')

@section ('content')
    {{--{!! dd(old('unit', $round->first()->unit)) !!}--}}

    {{-- <div class="container"> --}}
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Update New Round
                    <a href="{{route('rounds')}}">
                        <button type="submit" class="btn btn-default pull-right">
                            <i class="fa fa-backward" >  Back</i>
                        </button>
                    </a>
                </div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('updateround', urlencode($round->first()->name)) }}">
                        {{ csrf_field() }}
                        <input type="text" name="roundid" hidden value="{{$round->first()->roundid}}">

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="event" class="col-md-4 control-label">Name</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="name" value="{{ old('name', $round->first()->name) }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="event" class="col-md-4 control-label">Parent Organisation</label>

                            <div class="col-md-6">
                                <input type="text" hidden id="organisationvalue" value="{{old('organisationid', $round->first()->organisationid) }}">
                                <select name="parentorganisationid" class="form-control" id="organisationselect">
                                    <option value="null">None</option>
                                    @foreach ($organisations as $organisation)
                                        <option value="{{$organisation->organisationid}}">{{$organisation->name}}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
                            <label for="event" class="col-md-4 control-label">Round Code</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="code" value="{{ old('code', $round->first()->code) }}" required autofocus>

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
                                <textarea class="form-control" name="description" required autofocus >{{ old('description', $round->first()->description) }}</textarea>

                                @if ($errors->has('description'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('unit') ? ' has-error' : '' }}">
                            <label for="event" class="col-md-4 control-label">Round Code</label>

                            <input type="text" hidden id="roundsunitvalue" value="{{old('unit', $round->first()->unit) }}">
                            <div class="col-md-4">
                                <select name="unit" class="form-control" id="roundsunit">
                                    <option value="m">Meters</option>
                                    <option value="y">Yards</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('dist1') ? ' has-error' : '' }}">
                            <label for="event" class="col-md-4 control-label">Distance 1</label>

                            <div class="col-md-2">
                                <input type="text" class="form-control" name="dist1" value="{{ old('dist1', $round->first()->dist1) }}"  required autofocus>

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
                                <input type="text" class="form-control" name="dist1max" value="{{ old('dist1max', $round->first()->dist1max) }}" required autofocus >

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
                                <input type="text" class="form-control" name="dist2" value="{{ old('dist2', $round->first()->dist2) }}" >

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
                                <input type="text" class="form-control" name="dist2max" value="{{ old('dist2max', $round->first()->dist2max) }}" >

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
                                <input type="text" class="form-control" name="dist3" value="{{ old('dist3', $round->first()->dist3) }}" >

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
                                <input type="text" class="form-control" name="dist3max" value="{{ old('dist3max', $round->first()->dist3max) }}" >

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
                                <input type="text" class="form-control" name="dist4" value="{{ old('dist4', $round->first()->dist4) }}" >

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
                                <input type="text" class="form-control" name="dist4max" value="{{ old('dist4max', $round->first()->dist4max) }}" >

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
                                <input type="text" class="form-control" name="totalmax" value="{{ old('totalmax', $round->first()->totalmax) }}" >

                                @if ($errors->has('totalmax'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('totalmax') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('totalx') ? ' has-error' : '' }}">
                            <label for="event" class="col-md-4 control-label">Max Total X-Count</label>

                            <div class="col-md-2">
                                <input type="text" class="form-control" name="totalx" value="{{ old('totalx', $round->first()->totalx) }}" >

                                @if ($errors->has('totalx'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('totalx') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('total10') ? ' has-error' : '' }}">
                            <label for="event" class="col-md-4 control-label">Max Total 10-Count</label>

                            <div class="col-md-2">
                                <input type="text" class="form-control" name="total10" value="{{ old('total10', $round->first()->total10) }}" >

                                @if ($errors->has('total10'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('total10') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="checkbox">
                                <label class="col-md-4 control-label">Visible</label>
                                <div class="col-md-6">
                                    <?php
                                        $status='';
                                        if ($round->first()->visible == 1) {
                                            $status = 'checked';
                                        }
                                    ?>
                                    <input type="checkbox" name="visible" {{$status}}>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Update
                                </button>
                                <a href="{!! route('deleteround', [$round->first()->roundid, urlencode($round->first()->name)]) !!}" class="btn btn-danger pull-right" role="button" id="deleteBtn">
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

@endsection

