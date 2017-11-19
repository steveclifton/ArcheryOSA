@extends ('home')

@section ('title')Home @endsection

@section ('content')

    @if (Auth::check())
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">My Current Events</h3>
                    </div>

                    @if (empty($userevents))
                        <div class="box-body">
                            <table>
                                <thead>
                                    <tr>
                                        <th>
                                            <div class="alert alert-default">
                                                <span>No Entries Yet</span>
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    @else
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table no-margin">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Start Date</th>
                                            <th>Event Status</th>
                                            <th>Entry Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($userevents as $event)
                                        <tr>
                                            <td>
                                                <a href="{{route('eventdetails', [urlencode($event->name)])}}">{{$event->name}}</a>
                                            </td>
                                            <td>{{date('d/m/Y', strtotime($event->startdate))}}</td>

                                            <?php
                                            switch ($event->eventstatus) :

                                                case 'open' :
                                                    $colour = 'limegreen';
                                                    break;
                                                case 'in-progress':
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
                                                default :
                                                    $colour = 'black';
                                                    break;
                                            endswitch;
                                            ?>
                                            <td style="color: {{$colour}}">
                                                <strong>{!! ucwords(str_replace('-', ' ', $event->eventstatus)) !!}</strong>
                                            </td>
                                            <?php
                                            switch ($event->usereventstatus) {
                                                case 'pending' :
                                                    $label = 'label label-warning';
                                                    break;
                                                case 'entered' :
                                                    $label = 'label label-success';
                                                    break;
                                                case 'waitlist' :
                                                    $label = 'label label-primary';
                                                    break;
                                                case 'rejected' :
                                                    $label = 'label label-danger';
                                                    break;
                                                default :
                                                    $label = 'label label-primary';
                                                    break;
                                            }
                                            ?>
                                            <td>
                                                <span class="{{$label}}">{!! ucwords($event->usereventstatus) !!}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    {{--Upcoming Events--}}
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Upcoming Events</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table no-margin">
                        <thead>
                            <tr>
                                <th style="width: 25%;">Name</th>
                                <th style="width: 30%">Location</th>
                                <th>Enteries Close</th>
                                <th>Start</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($events as $event)
                            <tr>
                                <td>
                                    <a href="{{route('eventdetails', [urlencode($event->name)])}}">{{$event->name}}</a>
                                </td>
                                <td>{{ (strlen($event->location) > 60) ? mb_substr($event->location, 0, 60) . ".." : $event->location }}</td>
                                <td><?= !empty($event->closeentry) ? date('d-m-Y', strtotime($event->closeentry)) : ''  ?></td>
                                <td>{{date('d-m-Y', strtotime($event->startdate)) }}</td>
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
                                <td style="color: {{$colour}}"><strong>{!! ucwords($event->status) !!}</strong></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.table-responsive -->
            </div>
            <!-- /.box-footer -->
        </div>
        </div>
    </div>

@endsection

