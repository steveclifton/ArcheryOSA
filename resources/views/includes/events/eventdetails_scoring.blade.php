<div class="col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div>
                <div class="box box-success">
                    <div class="box-header with-border">
                        <div>
                            <h1 class="box-title">Scoring</h1>
                        </div>
                        <div class="box-tools pull-right" >
                            <button type="button" class="btn btn-box-tool" data-widget="collapse">Click to Open &nbsp;
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Scoring</th>
                                    <th>Name</th>
                                    <th class="hidden-xs">Location</th>
                                    <th>Round</th>
                                </tr>
                                </thead>

                                <tbody>
                                    @foreach($eventrounds as $eventround)
                                        <tr onmouseover="this.style.backgroundColor='lightgrey'" onmouseout="this.style.backgroundColor='white'">
                                            <td>
                                                <a href="{{ route('getenterscoreview', [$eventround->eventroundid, $event->eventid, urlencode($event->name)]) }}">
                                                    <i class="fa fa-bullseye" aria-hidden="true"></i> Score
                                                </a>
                                            </td>
                                            <td>{{ ucwords($eventround->roundname) }}</td>
                                            <td class="hidden-xs">{{ ucwords($eventround->location) }}</td>
                                            <td>{{ ucwords($eventround->name) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>