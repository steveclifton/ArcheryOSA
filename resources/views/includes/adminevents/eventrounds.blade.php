
    <h3 style="text-align: center;padding-bottom: 5%">Event Rounds</h3>


<form class="form-horizontal" method="POST" action="{{ route('updateevent', $event->eventid) }}">
    {{ csrf_field() }}
    @foreach ($eventrounds as $eventround)
        <div class="form-group">
            <a href="{{route('updateeventroundview', $eventround->eventroundid)}}">
                <label for="eventround" class="col-md-4 control-label">{{$eventround->name}}</label>
            </a>
            <div class="col-md-3">
                <input type="text" class="form-control" name="cost" disabled placeholder="{{$eventround->name ?? ''}}">Round
            </div>

            <div class="col-md-3">
                <input type="text" class="form-control" name="cost" disabled placeholder="{!! ($eventround->date == 'weekly' || $eventround->date == 'daily') ? ucfirst($eventround->date) : date('d-m-Y', strtotime($eventround->date)) !!}">Date
            </div>
        </div>
    @endforeach
    <div class="col-md-6 col-md-offset-1">
        <button type="submit" class="btn btn-primary pull-right" id="savebutton" value="createeventround" name="submit" >
            Add Event Round
        </button>
    </div>
</form>