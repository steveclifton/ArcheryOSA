@section ('dashboard')
    <h1></h1>
@endsection

@include('layouts.title', ['title'=>'Events'])

@extends ('home')

@section ('content')

    {{-- <div class="container"> --}}
    <div class="row">
        <div class="col-xs-12">
            <div class="box">

                <div class="box-header">

                    <h3 class="box-title">Events</h3>

                    <div class="box-tools">
                        <div class="input-group input-group-sm" style="width: 300px;">
                            <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

                            <div class="input-group-btn" style="padding-left: 20px">
                                <a href="{{route('createevent')}}">
                                <button type="submit" class="btn btn-default" id="addevent">
                                    <i class="fa fa-plus-square"></i>
                                </button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Location</th>
                            <th>Visible</th>
                        </tr>

                        @foreach($events as $event)
                            <tr>
                                <td>{{$event->eventid}}</td>
                                <td><a href="{{ route('updateeventview', urlencode($event->eventid)) }}">{{$event->name}}</a></td>
                                <td>{{ (strlen($event->location) > 60) ? mb_substr($event->location, 0, 60) . ".." : $event->location }}</td>
                                <td>{!! ($event->visible) ? '<i class="fa fa-check"></i>' : '';  !!}</td>
                            </tr>
                        @endforeach


                    </table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
    {{-- </div> --}}

@endsection

