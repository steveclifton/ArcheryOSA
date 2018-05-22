<h3 style="text-align: center;padding-bottom: 5%">Edit Event</h3>

<div class="table-responsive">

    <div class="form-group" id="status" style="padding-bottom: 40px">

        <div class="col-md-4 col-sm-4 col-md-offset-4 col-sm-offset-4">
            <select name="selectround" class="form-control" id="selectround">
                @foreach ($daterange as $date)
                    <option value="{!! $date !!}" {!! $dateset == $date ? 'selected' : '' !!}>{!! date('d F', strtotime($date)) !!}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>


<form class="form-horizontal" method="POST" action="{{ route('updateeventtargetallocation')}}" id="eventformupdate" enctype="multipart/form-data">
    {{ csrf_field() }}
    <input type="hidden" name="eventid" value="{{ $event->first()->eventid }}">

    <div class=" table-condensed table-striped table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th class="col-md-4 col-sm-4 col-xs-4">Target Number</th>
                    <th>Name</th>
                </tr>
            </thead>
            <tbody>
                @foreach($evententries as $entry)
                    <tr>
                        <td>
                            <div class="col-md-4 col-sm-4 col-xs-4">
                                <input id="targetallocation" type="text" class="form-control" name="name[{{$entry->evententryid}}]" value="{{$entry->targetallocation ?? ''}}">
                            </div>
                        </td>
                        <td>
                            {{ ucwords($entry->fullname) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="form-group">
        <div class="col-md-6 col-md-offset-4">
            <button type="submit" class="btn btn-success" id="savebutton" value="save" name="submit">
                Save
            </button>
        </div>
    </div>


</form>
