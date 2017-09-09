$(document).ready(function() {
    var selectedValue = $('#roundsunitvalue').val();
    $('#roundsunit option[value=' + selectedValue +']').attr('selected','selected');

    var selectedValue = $('#clubscountryvalue').val();
    $('#clubscountry option[value=' + selectedValue +']').attr('selected','selected');

});