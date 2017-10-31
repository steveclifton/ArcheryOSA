@section ('dashboard')
    <h1></h1>
@endsection

@extends ('home')

@section ('title')Edit Division @endsection

@section ('content')
    {{--{!! dd($division); !!}--}}
    {{-- <div class="container"> --}}
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Update New Division
                    <a href="{{route('divisions')}}">
                        <button type="submit" class="btn btn-default pull-right" id="addevent">
                            <i class="fa fa-backward" >  Back</i>
                        </button>
                    </a>
                </div>

                <div class="panel-body">

                    <h3 class="updateheader">{{$division->first()->name}}</h3><br>

                    <form class="form-horizontal" method="POST" action="{{ route('updatedivision', urlencode($division->first()->name)) }}">
                        {{ csrf_field() }}
                        <input type="text" name="divisionid" hidden value="{{$division->first()->divisionid}}">

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="event" class="col-md-4 control-label">Division Name*</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="name" value="{{ old('name', $division->first()->name) }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="event" class="col-md-4 control-label">Parent Organisation <h6>Select an organisation<br> the division belongs to</h6></label>

                            <div class="col-md-6">
                                <input type="text" hidden id="organisationvalue" value="{{old('organisation', $division->first()->organisationid) }}">
                                <select name="organisationid" class="form-control" id="organisationselect">
                                    <option value="null">None</option>
                                    @foreach ($organisations as $organisation)
                                        <option value="{{$organisation->organisationid}}">{{$organisation->name}}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('agerange') ? ' has-error' : '' }}">
                            <label for="event" class="col-md-4 control-label">Age Range</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="agerange" value="{{ old('agerange', $division->first()->agerange) }}" >

                                @if ($errors->has('agerange'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('agerange') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
                            <label for="event" class="col-md-4 control-label">Code</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="code" value="{{ old('code', $division->first()->code) }}">

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
                                <textarea class="form-control" name="description" >{{ old('description', $division->first()->description) }}</textarea>

                                @if ($errors->has('description'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="checkbox">
                                <label class="col-md-4 control-label">Visible</label>
                                <div class="col-md-6">
                                    @if (!empty($division))
                                            <?php
                                            $status='';
                                            if ($division->first()->visible == 1) {
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
                                <button type="submit" class="btn btn-primary">
                                        Update
                                </button>

                                <a href="{!! route('deletedivision', [$division->first()->divisionid, urlencode($division->first()->name)]) !!}" class="btn btn-danger pull-right" role="button" id="deleteBtn">
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

