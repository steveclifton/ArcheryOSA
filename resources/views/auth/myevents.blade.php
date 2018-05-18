@extends ('home')

@section ('title')My Events @endsection

@section ('content')
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
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
                                        <th>Name</th>
                                        <th>Date</th>
                                        <th>Event Status</th>
                                        <th>Entry Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($userevents as $event)
                                        <tr>
                                            <td>
                                                <a href="{{route('eventdetails', ['eventurl' => $event->url])}}">{{$event->name}}</a>
                                            </td>
                                            <td>{{date('d/m/Y', strtotime($event->startdate))}}</td>

                                            <?php
                                            switch ($event->eventstatus) :
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
                                                default :
                                                    $colour = 'black';
                                                    break;
                                            endswitch;
                                            ?>
                                            <td style="color: {{$colour}}">
                                                <strong>{!! ucwords($event->eventstatus) !!}</strong>
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
                @endif
            </div>
        </div>
    </div>

@endsection

