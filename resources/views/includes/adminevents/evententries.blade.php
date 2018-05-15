<h3 style="text-align: center;padding-bottom: 5%">Event Entries</h3>

<div class="table-responsive ">
    <form class="form-horizontal" method="POST" action="{{route('updateregistrationstatus', $event->eventid)}}" id="eventformupdate">
        {{ csrf_field() }}

        <table class="table userentry">
            <thead>
            <tr>
                <th>Archers Name</th>
                <th>Division</th>
                <th>Status</th>
                <th>Paid</th>
                <th>Date</th>
                <th>Email Sent</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                @php
                    switch ($user->entrystatusid) {
                        case 1 :$colour = '#FAD9AE';break;
                        case 2 :$colour = '#CDFAAE';break;
                        case 3 :$colour = '#AEDAFA';break;
                        case 4 :$colour = '#FAAEAE';break;
                    }

                @endphp

                <tr onmouseover="this.style.backgroundColor='lightgrey'" onmouseout="this.style.backgroundColor='{{$colour}}'" style="background: {{$colour}}">
                    <input type="hidden" name="userid[]" value="{{$user->userid}}">
                    <input type="hidden" name="divisionid[]" value="{{$user->divisionid}}">
                    <td>
                        <a href="{{route('userentrydetails', [urlencode($event->first()->name), $user->hash])}}">{!! ucwords(strtolower($user->fullname)) !!}</a>
                    </td>
                    <td>{{$user->division}}</td>
                    <td>
                        <select name="userstatus[]" id="userstatusselect">
                            @foreach($entrystatus as $status)
                                <option value="{{$status->entrystatusid}}" <?= ($user->entrystatusid == $status->entrystatusid) ? 'selected' : '' ?>>
                                    {{$status->name}}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>

                        <select name="userpaid[]" id="userpaidselect">
                            <option value="0" <?php echo ($user->paid == 0) ? 'selected' : '' ?>>No</option>
                            <option value="1" <?php echo ($user->paid == 1) ? 'selected' : '' ?>>Yes</option>
                            <option value="2" <?php echo ($user->paid == 2) ? 'selected' : '' ?>>N/A</option>
                        </select>
                    </td>
                    <td>{!! date('Y-m-d', strtotime($user->created_at)) !!}</td>

                    <td><input type="checkbox" disabled value="{{$user->confirmationemail}}" {!! $user->confirmationemail ? 'checked' : '' !!}></td>

                </tr>
            @endforeach
            </tbody>
        </table>

        <a href="{{route('adminaddarcher', [$event->first()->eventid, $event->first()->hash])}}" class="btn btn-info" role="button">Add Archer</a>

        <a href="{{route('exportevententries', [$event->first()->eventid, $event->first()->hash])}}" class="btn btn-warning col-md-offset-9" role="button">Export</a>



        <button type="submit" class="btn btn-success pull-right" value="update" name="update">
            Update
        </button>


    </form>
</div>
