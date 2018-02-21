@section ('dashboard')
    <h1></h1>
@endsection

@extends ('home')

@section ('title')User Entry @endsection

@section ('content')
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            @include('includes.session_errors')
        </div>
    </div>

    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">User Entry
                    <a href="{{URL::previous()}}">
                        <button type="submit" class="btn btn-default pull-right" id="addevent">
                            <i class="fa fa-backward" > Back</i>
                        </button>
                    </a>
                </div>


                <div class="panel-body">
                    <table class="table table-striped narrowth">
                        <tbody>
                        {{--{!! dd($user)!!}--}}
                        <tr>
                            <th width="40%">Name</th>
                            <td>{!! $user->first()->fullname !!}</td>
                        </tr>
                        <tr>
                            <th>Entry Status</th>
                            <td>{{$userdetails->entrystatus}}</td>
                        </tr>
                        <tr>
                            <th>Club</th>
                            <td>{{$userdetails->club}}</td>
                        </tr>
                        <tr>
                            <th>Membership Number</th>
                            <td>{{$user->first()->membershipcode}}</td>
                        </tr>

                        <tr>
                            <th>Division</th>
                            <td>{{$userdetails->division}}</td>
                        </tr>

                        <tr>
                            <th>Email</th>
                            <td>{{$user->first()->email}}</td>
                        </tr>
                        <tr>
                            <th>Phone number</th>
                            <td>{{$user->first()->phone}}</td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td>{{$user->first()->address}}</td>
                        </tr>

                        <tr>
                            <th>Gender</th>
                            <td>{!! $user->first()->gender == 'M' ? 'Male' : 'Female' !!}</td>
                        </tr>
                        <tr>
                            <th>Notes</th>
                            <td>{{$user->first()->notes}}</td>
                        </tr>
                        <tr>
                            <th>Rounds</th>
                            <td>
                                @foreach($userdetails->rounds as $r)
                                    {{$r->name}} <br>
                                @endforeach
                            </td>
                        </tr>
                        </tbody>

                    </table>

                </div>
            </div>
        </div>
    </div>
    {{-- </div> --}}



@endsection

