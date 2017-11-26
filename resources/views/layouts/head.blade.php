
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<meta property="og:title" content="ArcheryOSA">
	<meta property="og:description" content="Online Archery Event Management System">
	<meta property="og:image" content="{{public_path('ArcheryOSA.jpg')}}">
	<meta property="og:url" content="http://archeryosa.com">

	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-109735416-1"></script>
	<script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-109735416-1');
	</script>



	<!-- Google Tag Manager -->
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-K87KGQS');
	</script>
	<!-- End Google Tag Manager -->


	{{-- My CSS --}}
	<link rel="stylesheet" href="{{URL::asset('css/my.css')}}">

	<link rel="stylesheet" href="//cdn.datatables.net/plug-ins/1.10.16/integration/font-awesome/dataTables.fontAwesome.css">
	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<!-- Bootstrap 3.3.7 -->
	<link rel="stylesheet" href="{{URL::asset('bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="{{URL::asset('bower_components/font-awesome/css/font-awesome.min.css')}}">
	<!-- Ionicons -->
	<link rel="stylesheet" href="{{URL::asset('bower_components/Ionicons/css/ionicons.min.css')}}">
	<!-- Theme style -->
	<link rel="stylesheet" href="{{URL::asset('css/AdminLTE.css')}}">
	<!-- AdminLTE Skins. Choose a skin from the css/skins
	folder instead of downloading all of them to reduce the load. -->
	<link rel="stylesheet" href="{{URL::asset('css/skins/skin-black.min.css')}}">
	<!-- Morris chart -->
	<link rel="stylesheet" href="{{URL::asset('bower_components/morris.js/morris.css')}}">
	<!-- jvectormap -->
	<link rel="stylesheet" href="{{URL::asset('bower_components/jvectormap/jquery-jvectormap.css')}}">
	<!-- Date Picker -->
	<link rel="stylesheet" href="{{URL::asset('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
	<!-- Daterange picker -->
	<link rel="stylesheet" href="{{URL::asset('bower_components/bootstrap-daterangepicker/daterangepicker.css')}}">
	<!-- bootstrap wysihtml5 - text editor -->
	<link rel="stylesheet" href="{{URL::asset('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css')}}">
	<!-- Google Font -->
	<link rel="stylesheet" href="{{URL::asset('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic')}}">
	<!-- Bootstrap time Picker -->
	<link rel="stylesheet" href="{{URL::asset('../../plugins/timepicker/bootstrap-timepicker.min.css')}}">

	<script src='https://www.google.com/recaptcha/api.js'></script>