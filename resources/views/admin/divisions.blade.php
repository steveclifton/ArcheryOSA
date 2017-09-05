@include('layouts.title', ['title'=>'Divisions'])

@extends ('home')

@section ('content')

    {{-- <div class="container"> --}}
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Divisions</div>
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('create-new-event') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('firstname') ? ' has-error' : '' }}">
                            <label for="event" class="col-md-4 control-label">Event Name</label>

                            <div class="col-md-6">
                                <input id="event" type="text" class="form-control" name="event" value="{{ old('event') }}" required autofocus>

                                @if ($errors->has('event'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('event') }}</strong>
                                    </span>
                                @endif
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

