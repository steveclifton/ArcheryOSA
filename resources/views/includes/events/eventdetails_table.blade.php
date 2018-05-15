<tbody>

@if (!empty($userevententry))
    <tr>
        <th style="width: 25%">Status</th>
        <td>
            <strong style="color: limegreen">
                {!! ucwords($userevententry->status) !!}
            </strong>
        </td>
    </tr>
@endif

@if (!empty($userevententry))
    <tr>
        <th style="width: 25%">Paid</th>
        <td>
            <strong <?= ($userevententry->paid == 1) ? 'style="color: limegreen"' : '' ?> >
                {{ $userevententry->paid_label }}
            </strong>
        </td>
    </tr>
@endif

<tr>
    <th style="width: 25%">Start Date</th>
    <td>{{ date('d F Y', strtotime($event->startdate)) }}</td>
</tr>
<tr>
    <th style="width: 25%">End Date</th>
    <td>{{ date('d F Y', strtotime($event->enddate)) }}</td>
</tr>
<tr>
    <th style="width: 25%">Round(s)</th>
    <td>
        @foreach($eventrounds as $round)
            {{$round->name}} <br>
        @endforeach
    </td>
</tr>
<tr>
    <th style="width: 25%">Distances</th>
    <td>
        {{$event->distancestring}}
    </td>
</tr>
<tr>
    <th style="width: 25%">Event Type</th>
    <td>{!! ($event->eventtype == 1) ? 'Multi-week event' : 'Competition' !!}</td>
</tr>
<tr>
    <th style="width: 25%">Contact Name</th>
    <td>{{ $event->contact }}</td>
</tr>
<tr>
    <th style="width: 25%">Email</th>
    <td>{{ $event->email }}</td>
</tr>
<tr>
    <th style="width: 25%">Host Club</th>
    <td>{{ $event->hostclub }}</td>
</tr>
<tr>
    <th style="width: 25%">Location</th>
    <td>{{ ucwords($event->location) }}</td>
</tr>
<tr>
    <th style="width: 25%">Cost</th>
    <td>${{ $event->cost }}</td>
</tr>
<tr>
    <th style="width: 25%">Bank Details</th>
    <td>{{ $event->bankaccount}}</td>
</tr>
<tr>
    <th style="width: 25%">Bank Reference</th>
    <td>{{ $event->bankreference}}</td>
</tr>
<tr>
    <th style="width: 25%">Event Info</th>
    <td>{!!  nl2br($event->information) !!} </td>
</tr>
<tr>
    <th style="width: 25%">Schedule</th>
    <td></td>
</tr>
</tbody>