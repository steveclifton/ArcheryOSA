@extends ('home')

@section ('title')Users @endsection

@section ('content')



    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">All Users</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>User ID</th>
                                <th>User Type</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Event Entries</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>
                                        <img src="/content/profile/{{$user->image}}" alt="" height="42" width="42">
                                    </td>
                                    <td>
                                        <a href="{{route('getuserprofile', $user->userid)}}">
                                            {{$user->userid}}
                                        </a>
                                    </td>
                                    <td>{{ucwords(trim($user->usertype))}}</td>
                                    <td>{{ucwords(trim($user->firstname))}}</td>
                                    <td>{{ucwords(trim($user->lastname))}}</td>
                                    <td>{{ucwords(trim($user->email))}}</td>
                                    <td>{{$user->eventcount}}</td>

                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
    {{-- </div> --}}
@endsection