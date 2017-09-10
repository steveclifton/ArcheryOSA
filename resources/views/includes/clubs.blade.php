@extends ('home')

@section ('content')

    <div class="row">
        <section class="col-lg-10 col-md-offset-1">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Clubs</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <ul class="products-list product-list-in-box">

                        @foreach($clubs as $club)
                        <li class="item">
                            <div class="product-img">
                                @if (!empty($club->image))
                                    <img src="content/clubs/200/{{$club->image}}">
                                @else
                                    <img src="image/200.png">
                                @endif
                            </div>
                            <div class="product-info">
                                <a href="{{$club->url}}" target="_blank" class="product-title">{{ $club->name }}</a>
                                <span class="product-description">

                                </span>
                            </div>
                        </li>
                        @endforeach

                    </ul>
                </div>
                <!-- /.box-footer -->
                <div class="box-footer text-center">
                    <a href="javascript:;" class="uppercase">View More Results</a>
                </div>
            </div>
        </section>
    </div>

@endsection

