<div class="box-body">
    <div class="table-responsive">
        <table class="table table-striped">
            @include('includes.events.eventdetails_table')
        </table>

        <div>
            {!! nl2br($event->schedule)  !!}
        </div>

    </div>
</div>
