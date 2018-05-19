
<h3 style="text-align: center;padding-bottom: 5%">Edit Event Setup</h3>


<form class="form-horizontal" method="POST" action="{{ route('updateeventsetup', $event->first()->eventid) }}" id="eventformupdate" enctype="multipart/form-data">
    {{ csrf_field() }}
    <input type="hidden" name="eventid" value="{{ $event->first()->eventid }}">

    <div class="form-group">
        <label class="col-md-6 col-sm-6 col-xs-6 control-label">Visible of Event</label>
        <div class="col-md-6 col-sm-6 col-xs-1">
            <?php
            $status='';
            if ($event->first()->visible == 1) {
                $status = 'checked';
            }
            ?>
            <input style="margin-top: 10px" type="checkbox" name="visible" {{$status}}>
        </div>
    </div>

    <div class="form-group">
        <label class="col-md-6 col-sm-6 col-xs-6 control-label">Ignore Genders For Divisions</label>
        <div class="col-md-6 col-sm-6 col-xs-1">
            <?php
            $status='';
            if ($event->first()->ignoregenders == 1) {
                $status = 'checked';
            }
            ?>
            <input style="margin-top: 10px" type="checkbox" name="ignoregenders" {{$status}}>
        </div>
    </div>

    <div class="form-group">
        <label class="col-md-6 col-sm-6 col-xs-6 control-label">Date of Birth Required</label>
        <div class="col-md-6 col-sm-6 col-xs-1">
            <?php
            $status='';
            if ($event->first()->dob == 1) {
                $status = 'checked';
            }
            ?>
            <input style="margin-top: 10px" type="checkbox" name="dob" {{$status}}>
        </div>
    </div>

    <div class="form-group{{ $errors->has('multipledivisions') ? ' has-error' : '' }}">
        <label for="bankaccount" class="col-md-6 col-sm-6 col-xs-6 control-label">Allow Multiple Division Entries</label>
        <div class="col-md-6 col-sm-6 col-xs-1">
            @php
                $multipledivisions='';
                if ($event->first()->multipledivisions == 1) {
                    $multipledivisions = 'checked';
                }
            @endphp
            <input style="margin-top: 10px" type="checkbox" name="multipledivisions" {{$multipledivisions}}>
        </div>
    </div>

    <div class="form-group">
        <label class="col-md-6 col-sm-6 col-xs-6 control-label">Scoring Enabled</label>
        <div class="col-md-6 col-sm-6 col-xs-1">
            <?php
            $status='';
            if ($event->first()->scoringenabled == 1) {
                $status = 'checked';
            }
            ?>
            <input style="margin-top: 10px" type="checkbox" name="scoringenabled" {{$status}}>
        </div>
    </div>

    <div class="form-group">
        <label class="col-md-6 col-sm-6 col-xs-6 control-label">Users Can Score</label>
        <div class="col-md-6 col-sm-6 col-xs-1">
            <?php
            $status='';
            if ($event->first()->userscanscore == 1) {
                $status = 'checked';
            }
            ?>
            <input style="margin-top: 10px" type="checkbox" name="userscanscore" {{$status}}>
        </div>
    </div>

    @if ($event->first()->eventtype == 1)
        <div class="form-group">
            <div class="col-md-offset-4">
                <h5>Process Weekly League Scores</h5>

                <a href="{{route('processleague', [$event->first()->eventid, $event->first()->hash])}}" class="btn btn-info col-md-2 processleaguebtn" role="button">Process</a>

                <div class="col-md-6">
                    <input type="text" class="form-control" disabled value="{{'Last Run :' }}" >
                </div>
            </div>
        </div>
    @endif



    <div class="form-group">
        <div class="col-md-6 col-md-offset-4 col-xs-6 col-xs-offset-5" style="padding-top: 50px">
            <button type="submit" class="btn btn-success" id="savebutton" value="save" name="submit">
                Save
            </button>
        </div>
    </div>


</form>