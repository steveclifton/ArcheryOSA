<div class="col-md-12">
    <div class="row">
        <div class="box">
            <div class="box-body">

                <a href="{{ route('eventregistrationview', ['eventurl' => $event->url] ) }}" class="btn btn-warning pull-right eventButtons" role="button">
                    <i class="fa fa-mail-forward" aria-hidden="true"></i> Entry
                </a>

                <a class="btn btn-primary eventButtons pull-right" href="{{route('geteventresults', ['eventurl' => $event->url] ) }}" role="button">Results</a>

                @if ($canscore)
                    <a class="btn btn-success eventButtons pull-right" href="{{route('getscoringview', ['eventurl' => $event->url] ) }}" role="button">Scoring</a>
                @endif

                <a class="btn btn-info eventButtons pull-right" href="{{route('eventdetails', ['eventurl' => $event->url] ) }}" role="button">Details</a>

            </div>
        </div>
    </div>
</div>