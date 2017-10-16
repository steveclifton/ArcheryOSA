@section ('dashboard')
    <h1></h1>
@endsection

@include('layouts.title', ['title'=>'Upcoming Events'])

@extends ('home')

@section ('content')

    {{-- <div class="container"> --}}
    <div class="row">
        <div class="col-xs-12">
            <div class="box">

                <div class="box-header">

                    <h3 class="box-title">Upcoming Events</h3>

                    <div class="box-tools">
                        <div class="input-group input-group-sm" style="width: 300px;">
                            {{--<input type="text" name="table_search" class="form-control pull-right" placeholder="Search">--}}
                        </div>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tr>
                            <th style="width: 25%;" >Name</th>
                            <th style="width: 30%">Location</th>
                            <th class="hidden-xs hidden-sm">Enteries Close</th>
                            <th>Start</th>
                            <th>Status</th>
                        </tr>

                        @foreach($events as $event)
                            <tr>
                                <td>
                                    <a href="/eventdetails/{{ urlencode($event->eventid) }}">{{$event->name}}</a>
                                </td>
                                <td>{{ (strlen($event->location) > 30) ? mb_substr($event->location, 0, 30) . ".." : $event->location }}</td>
                                <td class="hidden-xs hidden-sm"><?= !empty($event->closeentry) ? date('d F Y', strtotime($event->closeentry)) : ''  ?></td>
                                <td>{{date('d F Y', strtotime($event->startdate)) }}</td>
                                <?php
                                    switch ($event->status) :
                                        case 'open' :
                                            $colour = 'limegreen';
                                            break;
                                        case 'entriesclosed' :
                                            $colour = 'orange';
                                            break;
                                        case 'completed' :
                                            $colour = 'red';
                                            break;
                                        case 'closed' :
                                            $colour = 'grey';
                                            break;
                                        case 'waitlist' :
                                            $colour = 'orange';
                                            break;
                                        case 'pending' :
                                            $colour = 'orange';
                                            break;
                                        case 'cancelled' :
                                            $colour = 'red';
                                            break;
                                    endswitch;
                                ?>

                                <td style="color: {{$colour}}">{!! ucwords($event->status) !!}</td>
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

