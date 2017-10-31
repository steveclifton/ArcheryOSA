@section ('dashboard')
    <h1></h1>
@endsection

@extends ('home')

@section ('title')Edit Organisation ID @endsection

@section ('content')

    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Edit Membership
                    <a href="{{route('profile')}}">
                        <button type="submit" class="btn btn-default pull-right" >
                            <i class="fa fa-backward" > Back</i>
                        </button>
                    </a>
                </div>


                <div class="panel-body">
                    {{--<h3 style="text-align: center;font-weight: bold;">{{$usermembership->first()->name}}</h3><br>--}}


                    <form class="form-horizontal" method="POST" action="{{ route('updateusermembership', $usermembership->first()->usermembershipid) }}" >
                        {{ csrf_field() }}

                        <div class="form-group {{ $errors->has('organisationid') ? ' has-error' : '' }}" id="organisationelement">
                            <label for="event" class="col-md-4 control-label">Organisation</label>
                            <input type="hidden" id="umidid" name="umid" value="{{ $usermembership->first()->usermembershipid }}">

                            <div class="col-md-6">
                                <input type="hidden" id="organisationvalue" name="organisationid" value="{{ old('organisationid') ?? $usermembership->first()->organisationid }}">

                                <select name="organisationid" class="form-control" id="organisationselecteventround" disabled required>
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

                        <div class="form-group{{ $errors->has('membershipcode') ? ' has-error' : '' }}">
                            <label for="membershipcode" class="col-md-4 control-label">Membership Code</label>

                            <input type="hidden" name="previousid" value="{{ $usermembership->first()->membershipcode }}">
                            <div class="col-md-6">
                                <input id="membershipcode" type="text" class="form-control" name="membershipcode" value="{{ old('membershipcode') ?? $usermembership->first()->membershipcode }}" required autofocus>

                                @if ($errors->has('membershipcode'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('membershipcode') }}</strong>
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

