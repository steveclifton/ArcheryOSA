@section ('dashboard')
    <h1></h1>
@endsection

@include('layouts.title', ['title'=>'Create Event'])

@extends ('home')

@section ('content')

    {{-- <div class="container"> --}}
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Create New Division</div>
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('createevent') }}">
                        {{ csrf_field() }}


                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="event" class="col-md-4 control-label">Divison Name</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Divison Name</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="organisation" class="col-md-4 control-label">Select</label>
                            <div class="col-md-6">
                                <select class="form-control" name="organisation">
                                    <option value="1">World Archery</option>
                                    <option value="2">IFAA</option>
                                    <option value="3">NZFAA</option>
                                    <option value="4">Archery NZ</option>
                                    <option value="5">Open</option>
                                </select>
                            </div>
                        </div>


                        Name
                        Age
                        Bowtype









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

