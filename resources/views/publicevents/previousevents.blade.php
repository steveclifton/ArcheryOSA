@section ('dashboard')
    <h1></h1>
@endsection

@extends ('home')

@section ('title')Previous Events @endsection

@section ('content')

    {{-- <div class="container"> --}}
    <div class="row">
        <div class="col-xs-12">
            <div class="box">

                <div class="box-header">

                    <h3 class="box-title">Previous Events</h3>

                    <div class="box-tools">
                        <div class="input-group input-group-sm" style="width: 300px;">
                            <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

                        </div>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">


                    <table class="table table-hover">
                        <tr>
                            <th style="width: 17%;">Name</th>
                            <th style="width: 40%">Location</th>
                            <th>Dates</th>
                            <th>Results</th>
                        </tr>

                        @foreach($events as $event)
                            <tr>
                                <td><a href="{{ route('updateeventview', urlencode($event->eventid)) }}">{{$event->name}}</a></td>
                                <td>{{ (strlen($event->location) > 60) ? mb_substr($event->location, 0, 60) . ".." : $event->location }}</td>
                                <td>{{date('d F Y', strtotime($event->startdate))}} - {{date('d F Y', strtotime($event->enddate))}}</td>
                                <td><a href="#">Results</a></td>
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

