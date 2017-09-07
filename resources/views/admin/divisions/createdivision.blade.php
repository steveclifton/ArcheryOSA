@section ('dashboard')
    <h1></h1>
@endsection

@if(!empty($division))
    @include('layouts.title', ['title'=>'Edit Division'])
@else
    @include('layouts.title', ['title'=>'Create Division'])
@endif


@extends ('home')

@section ('content')
    {{--{!! dd($division); !!}--}}
    {{-- <div class="container"> --}}
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    @if(!empty($division))
                        Update
                    @else
                        Create
                    @endif
                    New Division
                    <a href="{{route('divisions')}}">
                        <button type="submit" class="btn btn-default pull-right" id="addevent">
                            <i class="fa fa-backward" >  Back</i>
                        </button>
                    </a>
                </div>

                <div class="panel-body">

                    @if (!empty($division))
                        <form class="form-horizontal" method="POST" action="{{ route('updatedivision', urlencode($division->first()->name)) }}">
                            @else
                                <form class="form-horizontal" method="POST" action="{{ route('createdivision') }}">
                                    @endif
                                    {{ csrf_field() }}

                                    @if (!empty($division))
                                        <input type="text" name="divisionid" hidden value="{{$division->first()->divisionid}}">
                                    @endif


                                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                        <label for="event" class="col-md-4 control-label">Division Name</label>

                                        <div class="col-md-6">
                                            @if (!empty($division))
                                                <input type="text" class="form-control" name="name" value="{{ old('name', $division->first()->name) }}" required autofocus>
                                            @else
                                                <input type="text" class="form-control" name="name" required autofocus>
                                            @endif

                                            @if ($errors->has('name'))
                                                <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('agerange') ? ' has-error' : '' }}">
                                        <label for="event" class="col-md-4 control-label">Age Range</label>

                                        <div class="col-md-6">
                                            @if (!empty($division))
                                                <input type="text" class="form-control" name="agerange" value="{{ old('agerange', $division->first()->agerange) }}" >
                                            @else
                                                <input type="text" class="form-control" name="agerange" >
                                            @endif

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
                                            @if (!empty($division))
                                                <input type="text" class="form-control" name="code" value="{{ old('name', $division->first()->code) }}">
                                            @else
                                                <input type="text" class="form-control" name="code" >
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
                                            @if (!empty($division))
                                                <textarea class="form-control" name="description" required autofocus >{{ old('description', $division->first()->description) }}</textarea>
                                            @else
                                                <textarea class="form-control" name="description" required autofocus></textarea>
                                            @endif

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
                                                @else
                                                    <input type="checkbox" name="visible">
                                                @endif
                                            </div>
                                        </div>

                                    </div>


                                    <div class="form-group">
                                        <div class="col-md-6 col-md-offset-4">
                                            <button type="submit" class="btn btn-primary">
                                                @if (!empty($division))
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

