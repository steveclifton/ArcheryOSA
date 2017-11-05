@extends ('home')

@section ('title')Home @endsection

@section ('content')
    <div class="row">
        <div class="col-md-10 col-md-offset-1">

            {{--My Events--}}
            @if (Auth::check())
                <div>
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">My Current Events</h3>
                        </div>

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
                                                <a href="{{route('eventdetails', [$event->eventid, urlencode($event->name)])}}">{{$event->name}}</a>

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
                    </div>
                </div>
            @endif

            {{--Upcoming Events--}}
            <div>
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
                                            <a href="{{route('eventdetails', [$event->eventid, urlencode($event->name)])}}">{{$event->name}}</a>
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



        {{--Previous Events--}}
        {{--<div class="col-md-3">--}}
            {{--<div class="box box-primary">--}}
                {{--<div class="box-header with-border">--}}
                    {{--<h3 class="box-title">Results</h3>--}}
                {{--</div>--}}

                {{--<div class="box-body">--}}
                    {{--<ul class="products-list product-list-in-box">--}}
                        {{--<li class="item">--}}
                            {{--<div class="product-img">--}}
                                {{--<img src="content/clubs/aac.jpg">--}}
                            {{--</div>--}}
                            {{--<div class="product-info">--}}
                                {{--<a href="javascript:;" class="product-title">Empty</a>--}}
                                {{--<span class="product-description">--}}
                                    {{--<a href="#"><span class="label label-success pull-right"></span></a>--}}
                                {{--</span>--}}
                            {{--</div>--}}
                        {{--</li>--}}
                    {{--</ul>--}}
                {{--</div>--}}

                {{--<!-- /.box-footer -->--}}
                {{--<div class="box-footer text-center">--}}
                    {{--<a href="javascript:;" class="uppercase">View More Results</a>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}


    </div>

@endsection

