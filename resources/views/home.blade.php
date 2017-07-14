<?php

$user = false;
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

                <div class="row">
                    <section class="col-lg-4">

                        <!-- Calendar -->
                        <div class="box box-solid bg-green-gradient">
                            <div class="box-header">
                                <i class="fa fa-calendar"></i>
                                <h3 class="box-title">Calendar</h3>
                                <!-- tools box -->
                                <div class="pull-right box-tools">
                                    <!-- button with a dropdown -->
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                        <i class="fa fa-bars"></i></button>
                                        <ul class="dropdown-menu pull-right" role="menu">
                                            <li><a href="#">Add new event</a></li>
                                            <li><a href="#">Clear events</a></li>
                                            <li class="divider"></li>
                                            <li><a href="#">View calendar</a></li>
                                        </ul>
                                    </div>
                                    <button type="button" class="btn btn-default btn-sm" data-widget="collapse"><i class="fa fa-minus"></i>
                                    </button>

                                </div>
                                <!-- /. tools -->
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body no-padding">
                                <!--The calendar -->
                                <div id="calendar" style="width: 100%"></div>
                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer text-black">
                                <div class="row" >
                                    <div class="col-sm-12">
                                    <small><u>Upcoming Events</u></small>
                                        <!-- Progress bars -->
                                        <div class="clearfix">
                                            <span class="pull-left">Test Club Event</span>
                                            <small class="pull-right">{{date("d/m/Y", strtotime("+3 days"))}}</small>
                                        </div>

                                        <div class="clearfix">
                                            <span class="pull-left">National Indoor Champs</span>
                                            <small class="pull-right">{{date("d/m/Y", strtotime("+24 days"))}}</small>
                                        </div>
                                    {{--<div class="progress xs">
                                            <div class="progress-bar progress-bar-green" style="width: 70%;"></div>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
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