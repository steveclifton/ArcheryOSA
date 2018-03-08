<div class="col-md-12">
    <div class="row">
        <div class="col-xs-12 col-md-12 pull-right" >
            <div class="box">
                <div class="box-body">
                    @if (($event->visible || !empty($userevententry)) && $event->status != 'completed')
                        @if (!empty($userevententry))
                            <a href="{{ route('updateeventregistrationview', $event->eventid) }}" class="btn btn-warning pull-right eventButtons" role="button">My Entry</a>
                        @elseif($event->status == 'open')
                            <a href="{{ route('eventregistrationview', ['eventid' => $event->eventid, 'name' => urlencode($event->name)] ) }}" class="btn btn-primary pull-right eventButtons" role="button">
                                <i class="fa fa-mail-forward" aria-hidden="true"></i> Enter
                            </a>
                        @endif
                    @endif

                    @if ($event->eventid == 36 && !empty(Auth::user()->usertype ?? -1 == 1))
                        <a class="btn btn-primary eventButtons pull-right" href="{{route('geteventresults', urlencode($event->name)) }}" role="button">Results</a>
                    @endif

                    @if ($event->eventid == 33)
                        <a class="btn btn-primary eventButtons pull-right" href="{{route('geteventresults', urlencode($event->name)) }}" role="button">Results</a>
                    @endif

                    @if ($canscore)
                        <a class="btn btn-success eventButtons pull-right" href="{{route('getscoringview', [urlencode($event->name), $event->eventid ] ) }}" role="button">Scoring</a>
                    @endif

                    <a class="btn btn-info eventButtons pull-right" href="{{route('eventdetails', urlencode($event->name)) }}" role="button">Details</a>

                </div>
            </div>
        </div>
    </div>
</div>