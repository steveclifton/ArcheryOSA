<h3>Sponsorship</h3>

<form class="form-horizontal" method="POST" action="{{ route('updateeventsponsorship', $event->first()->eventid) }}" id="eventformupdate" enctype="multipart/form-data">
    {{ csrf_field() }}
    <input type="hidden" name="eventid" value="{{ $event->first()->eventid }}">
    <div class="form-group">
        <label class="col-md-4 control-label">Sponsored Event</label>
        <div class="col-md-6">

            @if (!empty($event))
                <?php
                $sponsored='';
                if ($event->first()->sponsored == 1) {
                    $sponsored = 'checked';
                }
                ?>

                <input type="checkbox" name="sponsored" style="margin-top: 10px" {{$sponsored}}>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('dtimage') ? ' has-error' : '' }}">
        <label for="desktopimage" class="col-md-4 control-label">Desktop Image (1000x400px)</label>

        <div class="col-md-6">
            <input type="file" class="form-control" name="dtimage">
            @if (!empty($event->first()->dtimage))
                <img id="blah" src="/content/sponsor/small/{{ (old('image')) ? old('image') : $event->first()->dtimage }}" alt="" style="width: 150px" />
            @endif

            @if ($errors->has('dtimage'))
                <span class="help-block">
                        <strong>{{ $errors->first('dtimage') }}</strong>
                    </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('dtimage') ? ' has-error' : '' }}">
        <label for="mobimage" class="col-md-4 control-label">Mobile Image(800x500px)</label>

        <div class="col-md-6">
            <input type="file" class="form-control" name="mobimage">
            @if (!empty($event->first()->mobimage))
                <img id="blah" src="/content/sponsor/small/{{ (old('image')) ? old('image') : $event->first()->mobimage }}" alt="" style="width: 150px" />
            @endif

            @if ($errors->has('mobimage'))
                <span class="help-block">
                        <strong>{{ $errors->first('mobimage') }}</strong>
                    </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('sponsorimageurl') ? ' has-error' : '' }}">
        <label for="sponsorimageurl" class="col-md-4 control-label">Sponsor Image URL</label>

        <div class="col-md-6">
            <input id="sponsorimageurl" type="text" class="form-control" name="sponsorimageurl" value="{{ old('sponsorimageurl') ?? $event->first()->sponsorimageurl }}" >

            @if ($errors->has('sponsorimageurl'))
                <span class="help-block">
                        <strong>{{ $errors->first('sponsorimageurl') }}</strong>
                    </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('sponsortext') ? ' has-error' : '' }}">
        <label for="sponsortext" class="col-md-4 control-label">Sponsor Text</label>

        <div class="col-md-6">
            <textarea rows="5" id="sponsortext" type="text" class="form-control" name="sponsortext" >{{ old('sponsortext') ?? $event->first()->sponsortext }}</textarea>
            @if ($errors->has('sponsortext'))
                <span class="help-block">
                        <strong>{{ $errors->first('sponsortext') }}</strong>
                    </span>
            @endif
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-6 col-md-offset-4 col-xs-6 col-xs-offset-5" style="padding-top: 50px">
            <button type="submit" class="btn btn-success" id="savebutton" value="save" name="submit">
                Save
            </button>
        </div>
    </div>

</form>