<div class="col-md-9">
    <div class="row">
        <div class="col-md-12">
            <div>
                <div class="box box-success collapsed-box">
                    <div class="box-header with-border">
                        <div>
                            <h1 class="box-title">Scoring</h1>
                        </div>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse">Click to Open &nbsp;
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <form class="form-horizontal" method="POST" action="" id="eventformupdate">
                                {{ csrf_field() }}
                                <table class="table">

                                    <thead>
                                    <tr>
                                        <th>Status</th>
                                        <th>Name</th>
                                        <th>Location</th>
                                        <th>Round</th>
                                        <th>Scoring</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($eventrounds as $eventround)
                                        <tr onmouseover="this.style.backgroundColor='lightgrey'" onmouseout="this.style.backgroundColor='white'">
                                            <td>{!! ucwords($event->status) !!}</td>
                                            <td>{{ ucwords($eventround->roundname) }}</td>
                                            <td>{{ ucwords($eventround->location) }}</td>
                                            <td>{{ ucwords($eventround->name) }}</td>
                                            <td>
                                                <a href="{{ route('enterscore', urlencode($event->name)) }}">
                                                    <i class="fa fa-bullseye" aria-hidden="true"></i> Score
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>

                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>