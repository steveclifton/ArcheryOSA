@section ('dashboard')
    <h1></h1>
@endsection

@include('layouts.title', ['title'=>'Edit Organisation'])

@extends ('home')

@section ('content')
    {{-- <div class="container"> --}}
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Update Organisation
                    <a href="{{route('organisations')}}">
                        <button type="submit" class="btn btn-default pull-right" id="addevent">
                            <i class="fa fa-backward" >  Back</i>
                        </button>
                    </a>
                </div>

                <div class="panel-body">
                    <h3 style="text-align: center;font-weight: bold;">{{$organisation->first()->name}}</h3><br>


                    <form class="form-horizontal" method="POST" action="{{ route('updateorganisation', urlencode($organisation->first()->name)) }}">
                        {{ csrf_field() }}

                        <input type="text" name="organisationid" hidden value="{{$organisation->first()->organisationid}}">

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="event" class="col-md-4 control-label">Organisation Name*</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="name" value="{{ old('name', $organisation->first()->name) }}" required autofocus>

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
                                <input type="text" hidden id="organisationvalue" value="{{old('organisation', $organisation->first()->parentorganisationid) }}">
                                <select name="parentorganisationid" class="form-control" id="organisationselect">
                                    <option value="null">None</option>
                                    @foreach ($organisations as $org)
                                        <option value="{{$org->organisationid}}">{{$org->name}}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('url') ? ' has-error' : '' }}">
                            <label for="event" class="col-md-4 control-label">URL</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="url" value="{{ old('url', $organisation->first()->url) }}" >

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
                                <input type="text" class="form-control" name="contactname" value="{{ old('name', $organisation->first()->contactname) }}">

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
                                <input type="email" class="form-control" name="email" value="{{ old('email', $organisation->first()->email) }}">

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
                                <input type="text" class="form-control" name="phone" value="{{ old('phone', $organisation->first()->phone) }}" >

                                @if ($errors->has('phone'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                            <label for="event" class="col-md-4 control-label">Description*</label>

                            <div class="col-md-6">
                                <textarea class="form-control" name="description" required autofocus >{{ old('description', $organisation->first()->description) }}</textarea>

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
                                    <?php
                                        $status='';
                                        if ($organisation->first()->visible == 1) {
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
                                <a href="{!! route('deleteorganisation', [$organisation->first()->organisationid, urlencode($organisation->first()->name)]) !!}" class="btn btn-danger pull-right" role="button" id="deleteBtn">
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

