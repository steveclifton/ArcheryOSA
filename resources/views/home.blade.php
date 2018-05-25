<!DOCTYPE html>
<html>
    <head>
        @include('layouts.head')
        <title>@yield('title')| Archery OSA</title>
        @include('layouts.scripts')
    </head>
    <body class="hold-transition skin-black sidebar-mini">
        <!-- Google Tag Manager (noscript) -->
        <noscript>
            <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-K87KGQS"
                          height="0" width="0" style="display:none;visibility:hidden"></iframe>
        </noscript>
        <!-- End Google Tag Manager (noscript) -->

        <script>
           // $(function () {
                if (typeof collapse_siderbar != 'undefined') {
                    if (collapse_siderbar) {
                        $('.sidebar-toggle').trigger('click');
                    }
                }
            //});
        </script>
        <div class="wrapper">
            <header class="main-header">
                <!-- Logo -->
                <a href="/" class="logo">
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
                            @if(Auth::check())
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
                    @yield('dashboard')
                </section>
                <!-- Main content -->
                <section class="content">

                    @yield('content')

                </section>
            </div>
            @include ('layouts.footer')
        </div>

    </body>
</html>





