<div class="box-body">

    <h4>
        <a href="javascript:;" id="adminbar" data-type="summary" data-eventid="{{$event->eventid}}">Summary</a>
    </h4>
    <hr>
    <h4>
        <a href="{{route('getscoringview', ['eventname' => urlencode($event->name), 'eventid' => $event->eventid])}}" id="adminbar" >Scoring</a>
    </h4>
    <hr>
    <h4>
        <a href="javascript:;" id="adminbar" data-type="edit" data-eventid="{{$event->eventid}}">Edit Event</a>
    </h4>
    <hr>
    <h4>
        <a href="javascript:;" id="adminbar" data-type="rounds" data-eventid="{{$event->eventid}}">Rounds</a>
    </h4>
    <hr>
    <h4>
        <a href="javascript:;" id="adminbar" data-type="entries" data-eventid="{{$event->eventid}}">Entries</a>
    </h4>
    <hr>
    <h4>
        <a href="javascript:;" id="adminbar" data-type="targets" data-eventid="{{$event->eventid}}">Target Allocation</a>
    </h4>
    <hr>

</div>