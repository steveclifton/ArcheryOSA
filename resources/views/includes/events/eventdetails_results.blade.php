<div class="col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div>
                <div class="box box-success collapsed-box">
                    <div class="box-header with-border">
                        <div>
                            <h1 class="box-title">Results</h1>
                        </div>
                        <div class="box-tools pull-right" >
                            <button type="button" class="btn btn-box-tool" data-widget="collapse">Click to Close &nbsp;
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <br>
                            @if ($event->eventtype == 1)
                                <div class="col-md-3" style="padding-bottom: 20px">
                                    <select class="week form-control" class="form-control">
                                        @foreach (range(1,10) as $week)
                                            <option
                                                    @if (isset($_GET['week']))
                                                        @if ( $_GET['week'] == $week) selected @endif
                                                    @endif value="{{$week}}">
                                                Week {{$week}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="col-md-1 col-xs-1 col-sm-1">Archer</th>
                                        <th class="col-md-1 col-xs-1 col-sm-1">Division</th>

                                        @if (true)
                                            <th class="col-md-1 col-xs-1 col-sm-1" >90m</th>
                                        @endif
                                        @if (true)
                                            <th class="col-md-1 col-xs-1 col-sm-1" >70m</th>
                                        @endif
                                        @if (true)
                                            <th class="col-md-1 col-xs-1 col-sm-1" >50m</th>
                                        @endif
                                        @if (true)
                                            <th class="col-md-1 col-xs-1 col-sm-1" >30m</th>
                                        @endif

                                        <th class="col-md-1 col-xs-1 col-sm-1" >Total</th>
                                        <th class="col-md-1 col-xs-1 col-sm-1" >Hits</th>
                                        <th class="col-md-1 col-xs-1 col-sm-1" >10s</th>
                                        <th class="col-md-1 col-xs-1 col-sm-1" >X</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach(range(1,5) as $user)

                                    <tr>
                                        <td class="hidden">
                                            <input type="hidden" name="userid[]" value="TEST"></td>
                                        <td>Steve Clifton-Hyphen</td>
                                        <td>Compound</td>

                                        @if (true)
                                            <td>345</td>
                                        @endif
                                        @if (true)
                                            <td>356</td>
                                        @endif
                                        @if (true)
                                            <td>358</td>
                                        @endif
                                        @if (true)
                                            <td>359</td>
                                        @endif

                                        <td>1425</td>
                                        <td>144</td>
                                        <td>104</td>
                                        <td>98</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>