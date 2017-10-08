@extends ('home')

@section ('content')


    <div class="row">
        <div class="col-md-8">

            {{--Upcoming Events--}}
            <div>
                <div class="box box-info">
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
                                <tbody>

                                @foreach($events as $event)
                                    <tr>
                                        <td><a href="/event/register/{{ urlencode($event->eventid) }}">{{$event->name}}</a></td>
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

                                        <td style="color: {{$colour}}">{!! ucwords($event->status) !!}</td>
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

            {{--My Events--}}
            <div>
                @if (Auth::check())
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">My Events</h3>
                        </div>

                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table no-margin">
                                    <thead>
                                        <tr>
                                            <th>Status</th>
                                            <th>Location</th>
                                            <th>Event</th>
                                            <th>Start Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><span class="label label-success">Confirmed</span></td>
                                            <td class="hidden-xs hidden-sm">Auckland Auckland Club</td>
                                            <td class="visible-xs visible-sm">Auckland</td>
                                            <td class=""><a href="#">Double WA 1440</a></td>
                                            <td>{{date('d/m/Y', strtotime("-3 days"))}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                @endif
            </div>
        </div>

        {{--Previous Events--}}
        <div class="col-md-4">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Previous Results</h3>
                </div>

                <div class="box-body">
                    <ul class="products-list product-list-in-box">
                        <li class="item">
                            <div class="product-img">
                                <img src="content/clubs/aac.jpg">
                            </div>
                            <div class="product-info">
                                <a href="javascript:;" class="product-title">WA 720</a>
                                <span class="product-description">
                                    Auckland Archery Club
                                    <a href="#"><span class="label label-success pull-right">Results</span></a>
                                </span>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- /.box-footer -->
                <div class="box-footer text-center">
                    <a href="javascript:;" class="uppercase">View More Results</a>
                </div>
            </div>
        </div>

    </div>




@endsection

