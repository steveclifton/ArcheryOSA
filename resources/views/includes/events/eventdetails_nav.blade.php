<div class="col-md-12">
    <div class="row">
        <div class="col-md-12 pull-right" >
            <div class="box">
                <div class="box-body">
                    @if ($event->visible || !is_null($userevententry))
                        @if (!is_null($userevententry))
                            <a href="{{ route('updateeventregistrationview', $event->eventid) }}" class="btn btn-warning pull-right eventButtons" role="button">
                                <i class="fa fa-gear" aria-hidden="true"></i> Update Entry
                            </a>
                        @else
                            <a href="{{ route('eventregistrationview', ['eventid' => $event->eventid, 'name' => urlencode($event->name)] ) }}" class="btn btn-primary pull-right eventButtons" role="button">
                                <i class="fa fa-mail-forward" aria-hidden="true"></i> Enter
                            </a>
                        @endif
                    @endif

                    @if (!empty($results))
                        <a class="btn btn-primary eventButtons pull-right" href="{{route('geteventresults', urlencode($event->name)) }}" role="button">Results</a>
                    @endif

                    @if ($event->scoringenabled && (($userevententry->entrystatusid ?? 0) == 2 || Auth::id() == $event->createdby))
                        <a class="btn btn-success eventButtons pull-right" href="{{route('getscoringview', urlencode($event->name)) }}" role="button">Scoring</a>
                    @endif

                        <a class="btn btn-info eventButtons pull-right" href="{{route('eventdetails', urlencode($event->name)) }}" role="button">Event Details</a>
                </div>
            </div>
        </div>
    </div>
</div>