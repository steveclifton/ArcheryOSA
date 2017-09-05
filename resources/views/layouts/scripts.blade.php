<!-- jQuery 3 -->
<script src="{{URL::asset('bower_components/jquery/dist/jquery.min.js')}}"></script>

<!-- jQuery UI 1.11.4 -->
<script src="{{URL::asset('bower_components/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script src="{{URL::asset('bower_components/select2/dist/js/select2.full.min.js')}}"></script>
<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>

<!-- InputMask -->
<script src="{{URL::asset('../../plugins/input-mask/jquery.inputmask.js')}}"></script>
<script src="{{URL::asset('../../plugins/input-mask/jquery.inputmask.date.extensions.js')}}"></script>
<script src="{{URL::asset('../../plugins/input-mask/jquery.inputmask.extensions.js')}}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{URL::asset('bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<!-- Morris.js charts -->
<script src="{{URL::asset('bower_components/raphael/raphael.min.js')}}"></script>
<script src="{{URL::asset('bower_components/morris.js/morris.min.js')}}"></script>
<!-- Sparkline -->
<script src="{{URL::asset('bower_components/jquery-sparkline/dist/jquery.sparkline.min.js')}}"></script>
<!-- jvectormap -->
<script src="{{URL::asset('plugins/jvectormap/jquery-jvectormap-1.2.2.min.js')}}"></script>
<script src="{{URL::asset('plugins/jvectormap/jquery-jvectormap-world-mill-en.js')}}"></script>
<!-- jQuery Knob Chart -->
<script src="{{URL::asset('bower_components/jquery-knob/dist/jquery.knob.min.js')}}"></script>
<!-- daterangepicker -->
<script src="{{URL::asset('bower_components/moment/min/moment.min.js')}}"></script>
<script src="{{URL::asset('bower_components/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
<!-- datepicker -->
<script src="{{URL::asset('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="{{URL::asset('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js')}}"></script>
<!-- Slimscroll -->
<script src="{{URL::asset('bower_components/jquery-slimscroll/jquery.slimscroll.min.js')}}"></script>
<!-- FastClick -->
<script src="{{URL::asset('bower_components/fastclick/lib/fastclick.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{URL::asset('js/adminlte.min.js')}}"></script>
<script src="{{URL::asset('js/archeryosa.js')}}"></script>

<script>
    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2();

        //Date range picker
        $('#reservation').daterangepicker();
        //Date range picker with time picker
        $('#reservationtime').daterangepicker({ timePicker: true, timePickerIncrement: 30, format: 'DD/MM/YYYY h:mm A' });


    });


</script>