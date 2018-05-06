@section ('dashboard')
    <h1></h1>
@endsection

@extends ('home')

@section ('title')Add Archer Relation @endsection

@section ('content')


    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            @include('includes.session_errors')
        </div>
    </div>

    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Create Child Account
                    <a href="{{route('profile')}}">
                        <button type="submit" class="btn btn-default pull-right" >
                            <i class="fa fa-backward" > Back</i>
                        </button>
                    </a>
                </div>

                <div class="panel-body">

                    <form class="form-horizontal" method="POST" action="{{ route('addchildaccount' ) }}" >
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('firstname') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Firstname</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="firstname"  required autofocus>

                                @if ($errors->has('firstname'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('firstname') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('lastname') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Lastname</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="lastname"  required autofocus>

                                @if ($errors->has('lastname'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('lastname') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Email</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="email" placeholder="Optional">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary" value="create" name="submit">
                                    Create
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

