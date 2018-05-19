
<h3 style="text-align: center;padding-bottom: 5%">Email ALL Event Entries</h3>


<form class="form-horizontal" method="POST" action="{{ route('sendeventupdateemail', $event->eventid) }}" id="eventformupdate" enctype="multipart/form-data">
    {{ csrf_field() }}
    <input type="hidden" name="eventid" value="{{ $event->eventid }}">

    <div class="form-group">
        <label for="email" class="col-md-4 control-label">Message</label>

        <div class="col-md-6">
            <textarea rows="6" type="text" class="form-control" name="message" required autofocus></textarea>
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-6 col-md-offset-4 col-xs-6 col-xs-offset-5" style="padding-top: 50px">
            <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to email EVERYONE?');">
                Send
            </button>
        </div>
    </div>


</form>