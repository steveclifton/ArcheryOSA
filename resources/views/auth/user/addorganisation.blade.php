@section ('dashboard')
    <h1></h1>
@endsection

@include('layouts.title', ['title'=>'Add Organisation ID'])

@extends ('home')

@section ('content')

    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Add Organisation ID
                    <a href="{{route('profile')}}">
                        <button type="submit" class="btn btn-default pull-right" >
                            <i class="fa fa-backward" > Back</i>
                        </button>
                    </a>
                </div>


                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('createorguserassoc') }}" >
                        {{ csrf_field() }}


                        <div class="form-group {{ $errors->has('organisationid') ? ' has-error' : '' }}" id="organisationelement">
                            <label for="event" class="col-md-4 control-label">Organisation</label>

                            <div class="col-md-6">
                                <select name="organisationid" class="form-control" id="organisationselecteventround" required>
                                    <option value="" disabled selected>Select Organisation</option>
                                    @foreach ($organisations as $organisation)
                                        <option value="{{$organisation->organisationid}}">{{$organisation->name}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('organisationid'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('organisationid') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('id') ? ' has-error' : '' }}">
                            <label for="id" class="col-md-4 control-label">Organisation ID</label>

                            <div class="col-md-6">
                                <input id="id" type="text" class="form-control" name="id" value="{{old('id')}}" required autofocus>

                                @if ($errors->has('id'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary" id="savebutton" value="save" name="submit">
                                    Save
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

