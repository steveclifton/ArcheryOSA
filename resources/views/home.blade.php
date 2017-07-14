<?php
$user = true;
$userName = 'Steve Clifton';
?>
<!DOCTYPE html>
<html>
    <head>
        @include('layouts.head')
        <title>Home | Archery OSA</title>
    </head>
    <body class="hold-transition skin-black sidebar-mini">
        <div class="wrapper">
            <header class="main-header">
                <!-- Logo -->
                <a href="javascript:;" class="logo">
                    <span class="logo-mini">OSA</span>
                    <span class="logo-lg"><b>Archery</b>OSA</span>
                </a>
                <!-- Header Navbar: style can be found in header.less -->
                <nav class="navbar navbar-static-top">
                    <!-- Sidebar toggle button-->
                    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                        <span class="sr-only">Toggle navigation</span>
                    </a>
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
                            @if($user)
                            @include('layouts.notification')
                            @include('layouts.tasks')
                            @endif
                            @include('layouts.useraccount')
                            @include('layouts.rightsidebar')
                        </ul>
                    </div>
                </nav>
            </header>
            {{-- Left Side Bar --}}
            @include('layouts.leftsidebar')
            {{-- Content Wrapper. Contains page content  --}}
            <div class="content-wrapper">
                {{-- Content Header (Page header) --}}
                <section class="content-header">
                    <h1>Dashboard</h1>
                </section>
                <!-- Main content -->
                <section class="content">

                    {{-- Row 1 --}}
                    <div class="row">

                        {{-- Upcoming Events --}}
                        <section class="col-lg-8">
                            <div class="box box-info">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Upcoming Events</h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <div class="table-responsive">
                                        <table class="table no-margin">
                                            <thead>
                                                <tr>
                                                    <th>Location</th>
                                                    <th>Event</th>
                                                    <th>Start Date</th>
                                                </tr>
                                            <tbody>
                                                <tr>
                                                    <td class="hidden-xs hidden-sm">Auckland Auckland Club</a></td>
                                                    <td class="visible-xs visible-sm">Auckland</td>
                                                    <td class=""><a href="#">Double WA 1440</a></td>
                                                    <td>{{date('d/m/Y', strtotime("-3 days"))}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="hidden-xs hidden-sm">Mt Green Archery Club</a></td>
                                                    <td class="visible-xs visible-sm">Auckland</td>
                                                    <td class=""><a href="#">Double 720 + Matchplay</a></td>
                                                    <td>{{date('d/m/Y', strtotime("+6 days"))}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="hidden-xs hidden-sm">Dunedin Archery Club</a></td>
                                                    <td class="visible-xs visible-sm">Dunedin</td>
                                                    <td class=""><a href="#">Double 720</a></td>
                                                    <td>{{date('d/m/Y', strtotime("+11 days"))}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="hidden-xs hidden-sm">Massey Archery Club</a></td>
                                                    <td class="visible-xs visible-sm">Auckland</td>
                                                    <td class=""><a href="#">IFAA Target Round</a></td>
                                                    <td>{{date('d/m/Y', strtotime("+14 days"))}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="hidden-xs hidden-sm">Christchurch Archery Club</a></td>
                                                    <td class="visible-xs visible-sm">Christchurch</td>
                                                    <td class=""><a href="#">Monthly Club Indoor Night</a></td>
                                                    <td>{{date('d/m/Y', strtotime("+17 days"))}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="hidden-xs hidden-sm">Riverglade Archery Club</a></td>
                                                    <td class="visible-xs visible-sm">Hamilton</td>
                                                    <td class=""><a href="#">Burton</a></td>
                                                    <td>{{date('d/m/Y', strtotime("+21 days"))}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="hidden-xs hidden-sm">Timaru Archery Club</a></td>
                                                    <td class="visible-xs visible-sm">Timaru</td>
                                                    <td class=""><a href="#">Double 720</a></td>
                                                    <td>{{date('d/m/Y', strtotime("+24 days"))}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="hidden-xs hidden-sm">Massey Archery Club</a></td>
                                                    <td class="visible-xs visible-sm">Auckland</td>
                                                    <td class=""><a href="#">IFAA Hunter Round</a></td>
                                                    <td>{{date('d/m/Y', strtotime("+34 days"))}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- /.table-responsive -->
                                </div>
                                <!-- /.box-footer -->
                            </div>
                        </section>


                        {{-- Previous Results --}}
                        <section class="col-lg-4">
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Previous Results</h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <ul class="products-list product-list-in-box">
                                        <li class="item">
                                            <div class="product-img">
                                                <img src="content/clubs/shore.jpeg">
                                            </div>
                                            <div class="product-info">
                                                <a href="javascript:;" class="product-title">Shore Archery Club</a>
                                                <span class="product-description">
                                                    Burton
                                                    <a href="#"><span class="label label-success pull-right">Results</span></a>
                                                </span>
                                            </div>
                                        </li>
                                        <!-- /.item -->
                                        <li class="item">
                                            <div class="product-img">
                                                <img src="content/clubs/mtg.jpeg">
                                            </div>
                                            <div class="product-info">
                                                <a href="javascript:;" class="product-title">Mountain Green Archery Club</a>
                                                <span class="product-description">
                                                    Double WA1440
                                                    <a href="#"><span class="label label-success pull-right">Results</span></a>
                                                </span>
                                            </div>
                                        </li>
                                        <!-- /.item -->
                                        <li class="item">
                                            <div class="product-img">
                                                <img src="content/clubs/wfa.jpeg">
                                            </div>
                                            <div class="product-info">
                                                <a href="javascript:;" class="product-title">Whitford Forrest Archers</a>
                                                <span class="product-description">
                                                    Bowhunter National Championships
                                                    <a href="#"><span class="label label-success pull-right">Results</span></a>
                                                </span>
                                            </div>
                                        </li>
                                        <!-- /.item -->
                                        <li class="item">
                                            <div class="product-img">
                                                <img src="content/clubs/aac.jpg">
                                            </div>
                                            <div class="product-info">
                                                <a href="javascript:;" class="product-title">Auckland Archery Club</a>
                                                <span class="product-description">
                                                    Double 720 + Matchplay
                                                    <a href="#"><span class="label label-success pull-right">Results</span></a>
                                                </span>
                                            </div>
                                        </li>
                                        <!-- /.item -->
                                    </ul>
                                </div>
                                <!-- /.box-footer -->
                                <div class="box-footer text-center">
                                  <a href="javascript:;" class="uppercase">View More Results</a>
                                </div>
                            </div>
                        </section>


                    </div>


                    {{-- Row 2 --}}
                    <div class="row">

                        {{-- My Events --}}
                        <section class="col-lg-8">
                            <div class="box box-info">
                                <div class="box-header with-border">
                                    <h3 class="box-title">My Events</h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <div class="table-responsive">
                                        <table class="table no-margin">
                                            <thead>
                                                <tr>
                                                    <th>Status</th>
                                                    <th>Location</th>
                                                    <th>Event</th>
                                                    <th>Start Date</th>
                                                </tr>
                                            <tbody>
                                                <tr>
                                                    <td><span class="label label-success">Confirmed</span></td>
                                                    <td class="hidden-xs hidden-sm">Auckland Auckland Club</a></td>
                                                    <td class="visible-xs visible-sm">Auckland</td>
                                                    <td class=""><a href="#">Double WA 1440</a></td>
                                                    <td>{{date('d/m/Y', strtotime("-3 days"))}}</td>
                                                </tr>
                                                <tr>
                                                    <td><span class="label label-success">Confirmed</span></td>
                                                    <td class="hidden-xs hidden-sm">Mt Green Archery Club</a></td>
                                                    <td class="visible-xs visible-sm">Auckland</td>
                                                    <td class=""><a href="#">Double 720 + Matchplay</a></td>
                                                    <td>{{date('d/m/Y', strtotime("+6 days"))}}</td>
                                                </tr>
                                                <tr>
                                                    <td><span class="label label-warning">Pending</span></td>
                                                    <td class="hidden-xs hidden-sm">Dunedin Archery Club</a></td>
                                                    <td class="visible-xs visible-sm">Dunedin</td>
                                                    <td class=""><a href="#">Double 720</a></td>
                                                    <td>{{date('d/m/Y', strtotime("+11 days"))}}</td>
                                                </tr>
                                                <tr>
                                                    <td><span class="label label-success">Confirmed</span></td>
                                                    <td class="hidden-xs hidden-sm">Massey Archery Club</a></td>
                                                    <td class="visible-xs visible-sm">Auckland</td>
                                                    <td class=""><a href="#">IFAA Target Round</a></td>
                                                    <td>{{date('d/m/Y', strtotime("+14 days"))}}</td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- /.table-responsive -->
                                </div>
                                <!-- /.box-footer -->
                            </div>
                        </section>


                    </div>


                </section>
            </div>
            @include ('layouts.footer')
        </div>
        @include('layouts.scripts')
    </body>
</html>





