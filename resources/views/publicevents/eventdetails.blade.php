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
                <div class="col-md-8">

                    <div>
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title">Event Details</h3>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <h3>Outdoor League Series 2017</h3>
                                <div class="table-responsive">
                                    <table class="table no-margin">
                                        <tbody>
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
                                                <td></td>
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
                                                <th>Schedule</th>
                                                <td>{{ $event->schedule }}</td>
                                            </tr>
                                            <tr>
                                                <th>Cost</th>
                                                <td>{{ $event->cost }}</td>
                                            </tr>
                                            <tr>
                                                <th>Bank Details</th>
                                                <td>{{ $event->bank }}</td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <div class="form-group">

                                        <div class="col-md-6 col-md-offset-4">

                                            <button type="submit" class="btn btn-primary" id="savebutton" value="save" name="submit">
                                                Update
                                            </button>

                                            <a href="{{route('eventregistrationview', $event->eventid)}}" class="btn btn-success" role="button">Enter</a>


                                        </div>

                                    </div>

                                </div>
                                <!-- /.table-responsive -->
                            </div>
                            <!-- /.box-footer -->
                        </div>
                    </div>


                </div>

                {{--Previous Events--}}
                <div class="col-md-4">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Current Entries</h3>
                        </div>

                        <div class="box-body">
                            <ul class="products-list product-list-in-box">
                                <li class="item">
                                    <div class="product-img">
                                        <img src="../content/clubs/aac.jpg">
                                    </div>
                                    <div class="product-info">
                                        <a href="javascript:;" class="product-title">WA 720</a>
                                        <span class="product-description">
                                    Auckland Archery Club
                                    <a href="#"><span class="label label-success pull-right">Results</span></a>
                                </span>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <!-- /.box-footer -->
                        <div class="box-footer text-center">
                            <a href="javascript:;" class="uppercase">View More Results</a>
                        </div>
                    </div>
                </div>

            </div>

            </div>
            <!-- /.box -->
        </div>
    </div>
    {{-- </div> --}}

@endsection

