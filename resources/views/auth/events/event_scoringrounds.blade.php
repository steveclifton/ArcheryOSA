<div class="row">
    <div class="col-md-12">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Scoring</h3>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Scoring</th>

                            @if ($event->eventtype == 1)
                                <th>Week</th>
                            @endif

                            <th>Name</th>
                            <th class="hidden-xs">Location</th>
                            <th>Round</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($eventrounds as $eventround)
                            <tr onmouseover="this.style.backgroundColor='lightgrey'" onmouseout="this.style.backgroundColor='white'">
                                <td>
                                    <a href="{{ route('getenterscoreview', [$eventround->eventroundid, $event->eventid, urlencode($event->name)]) }}">
                                        <i class="fa fa-bullseye" aria-hidden="true"></i> Score
                                    </a>
                                </td>

                                @if ($event->eventtype == 1)
                                    <td>{{$event->currentweek}}</td>
                                @endif

                                <td>{{ ucwords($eventround->roundname) }}</td>
                                <td class="hidden-xs">{{ ucwords($eventround->location) }}</td>
                                <td>{{ ucwords($eventround->name) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@if (!empty($users))

    @include('auth.events.event_scoringforms')
@endif