<div class="col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div>
                <div class="box box-success">
                    <div class="box-header with-border">
                        <div>
                            <h1 class="box-title">Sponsors and Prizes</h1>
                        </div>
                        <div class="box-tools pull-right" >
                            <button type="button" class="btn btn-box-tool" data-widget="collapse">Click to Close &nbsp;
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body" >
                        <a href="{!! $event->sponsorimageurl !!}" target="_blank">
                            <img class="dtimage" src="{!! '/content/sponsor/' . $event->dtimage !!}">
                            <img class="mobimage" src="{!! '/content/sponsor/' . $event->mobimage !!}">
                        </a>
                        <br>
                        <div class="col-md-9 col-md-offset-1">
                            {!! nl2br($event->sponsortext) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>