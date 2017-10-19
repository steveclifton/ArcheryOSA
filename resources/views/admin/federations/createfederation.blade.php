@section ('dashboard')
    <h1></h1>
@endsection


@include('layouts.title', ['title'=>'Create Federation'])



@extends ('home')

@section ('content')
{{--{!! dd($federation); !!}--}}
    {{-- <div class="container"> --}}
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Create New Federation
                    <a href="{{route('federations')}}">
                        <button type="submit" class="btn btn-default pull-right" id="addevent">
                            <i class="fa fa-backward" >Back</i>
                        </button>
                    </a>
                </div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('createfederation') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="event" class="col-md-4 control-label">Federation Name</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="name" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('url') ? ' has-error' : '' }}">
                            <label for="event" class="col-md-4 control-label">URL</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="url" >

                                @if ($errors->has('url'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('url') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('contactname') ? ' has-error' : '' }}">
                            <label for="event" class="col-md-4 control-label">Contact Person</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="contactname" >

                                @if ($errors->has('contactname'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('contactname') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="event" class="col-md-4 control-label">Email</label>

                            <div class="col-md-6">
                                <input type="email" class="form-control" name="email" >

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                            <label for="event" class="col-md-4 control-label">Phone</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="phone" >

                                @if ($errors->has('phone'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                            <label for="event" class="col-md-4 control-label">Description</label>

                            <div class="col-md-6">
                                <textarea class="form-control" name="description" required autofocus></textarea>

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
                                    <input type="checkbox" name="visible">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Create
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

