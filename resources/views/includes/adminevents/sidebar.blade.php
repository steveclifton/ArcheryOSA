<div class="box-body">

    <h4>
        <a href="javascript:;" id="adminbar" data-type="summary" data-eventid="{{$event->eventid}}">Summary</a>
    </h4>
    <hr>

    <h4>
        <a href="javascript:;" id="adminbar" data-type="edit" data-eventid="{{$event->eventid}}">Edit Event Details</a>
    </h4>
    <hr>
    <h4>
        <a href="javascript:;" id="adminbar" data-type="editsetup" data-eventid="{{$event->eventid}}">Edit Event Config</a>
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
        <a href="javascript:;" id="adminbar" data-type="emailall" data-eventid="{{$event->eventid}}">Emails</a>
    </h4>
    <hr>
    <h4>
        <a href="javascript:;" id="adminbar" data-type="sponsorship" data-eventid="{{$event->eventid}}">Sponsorship</a>
    </h4>
    <hr>
    <h4>
        <a href="javascript:;" id="adminbar" data-type="targets" data-eventid="{{$event->eventid}}">Target Allocation</a>
    </h4>
    <hr>
    <h4>
        <a href="{{route('getscoringview', ['eventurl' => $event->url])}}" id="adminbar" >Scoring</a>
    </h4>
    <hr>

</div>