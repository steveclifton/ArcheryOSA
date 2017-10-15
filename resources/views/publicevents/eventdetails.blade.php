@section ('dashboard')
    <h1></h1>
@endsection

@include('layouts.title', ['title'=>'Event Details'])

@extends ('home')

@section ('content')

    {{-- <div class="container"> --}}
    <div class="row">
        <div class="col-xs-12">
            <div class="row">
                <div class="col-md-9">
                    @if(session()->has('message'))
                        <div class="alert alert-success">
                            {{ session()->get('message') }}
                        </div>
                    @elseif (session()->has('failure'))
                        <div class="alert alert-danger">
                            {{ session()->get('failure') }}
                        </div>
                    @endif
                    <div>
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title">Event Details</h3>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">

                                @if (!is_null($userevententry))
                                    <a href="{{ route('updateeventregistrationview', $event->eventid) }}" class="btn btn-warning pull-right" role="button">
                                        <i class="fa fa-bullseye" aria-hidden="true"></i> Update
                                    </a>
                                @else
                                    <a href="{{route('eventregistrationview', $event->eventid)}}" class="btn btn-success pull-right" role="button">
                                        <i class="fa fa-bullseye" aria-hidden="true"></i> Enter
                                    </a>
                                @endif
                                <h3>{{$event->name}}</h3>
                                <div class="table-responsive">
                                    <table class="table">
                                        <tbody>

                                            @if (!is_null($userevententry))
                                                <tr>
                                                    <th>Status</th>
                                                    <td>
                                                        <strong style="color: limegreen">
                                                            {!! ucwords($userevententry->status) !!}
                                                        </strong>
                                                    </td>
                                                </tr>
                                            @endif

                                            @if (!is_null($userevententry))
                                                <tr>
                                                    <th>Paid</th>
                                                    <td>
                                                        <strong <?= ($userevententry->paid == 1) ? 'style="color: limegreen"' : '' ?> >
                                                            <?= ($userevententry->paid == 1) ? 'Paid' : 'Not Paid' ?>
                                                        </strong>
                                                    </td>
                                                </tr>
                                            @endif

                                            <tr>
                                                <th>Start Date</th>
                                                <td>{{ date('d F Y', strtotime($event->startdate)) }}</td>
                                            </tr>
                                            <tr>
                                                <th>End Date</th>
                                                <td>{{ date('d F Y', strtotime($event->enddate)) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Round(s)</th>
                                                <td>
                                                        {{$eventround->name}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Distances</th>
                                                <td>
                                                    {{$distances}}

                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Event Type</th>
                                                <td>{!! ($event->eventtype == 1) ? 'Multi-week event' : 'Competition' !!}</td>
                                            </tr>
                                            <tr>
                                                <th>Contact Name</th>
                                                <td>{{ $event->contact }}</td>
                                            </tr>
                                            <tr>
                                                <th>Email</th>
                                                <td>{{ $event->email }}</td>
                                            </tr>
                                            <tr>
                                                <th>Host Club</th>
                                                <td>{{ $event->hostclub }}</td>
                                            </tr>
                                            <tr>
                                                <th>Location</th>
                                                <td>{{ ucwords($event->location) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Cost</th>
                                                <td>${{ $event->cost }}</td>
                                            </tr>
                                            <tr>
                                                <th>Bank Details</th>
                                                <td>{{ $event->bank }}</td>
                                            </tr>
                                            <tr>
                                                <th>Schedule</th>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    {!! nl2br($event->schedule) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{--Previous Events--}}
                <div class="col-md-3">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Current Entries</h3>
                        </div>

                        <div class="box-body">
                            <ul class="products-list product-list-in-box">

                                @foreach ($users as $user)
                                    <li class="item">

                                        <span style="padding-right: 10%">
                                            <span class="label {{ $user->label }}">{{$user->division}}</span>
                                        </span>

                                        {{$user->fullname}}

                                    </li>
                                @endforeach

                            </ul>
                        </div>

                        <!-- /.box-footer -->
                        <div class="box-footer text-center">
                            <a href="javascript:;" class="uppercase">View More Entries</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




@endsection

