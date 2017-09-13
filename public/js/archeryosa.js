$(document).ready(function() {
    var selectedValueRound = $('#roundsunitvalue').val();
    $('#roundsunit option[value=' + selectedValueRound +']').attr('selected','selected');

    var selectedValueClubCountry = $('#clubscountryvalue').val();
    $('#clubscountry option[value=' + selectedValueClubCountry +']').attr('selected','selected');

    var selectedValueClubOrganisation = $('#organisationvalue').val();
    $('#organisationselect option[value=' + selectedValueClubOrganisation +']').attr('selected','selected');

    var selectedValueDivisionOrganisation = $('#organisationvalue').val();
    $('#organisationselect option[value=' + selectedValueDivisionOrganisation +']').attr('selected','selected');

    var selectedValueDivisionOrganisation = $('#organisationvalue').val();
    $('#organisationselect option[value=' + selectedValueDivisionOrganisation +']').attr('selected','selected');



    // Hide the round info on page load
    // $('#eventform').ready(function () {
    //     if ($('#pageid').val() == -1) {
    //         $('#dailydetails').hide();
    //         $('#savebutton').hide();
    //     }
    // });


    $('#divisionselect').find('label').each(function () {
        if ($(this).data('orgid') != '0') {
            $(this).css({'color': 'lightgrey'});
            $(this).find('input').attr("disabled", true);
        }
    });

    $('#organisationselect').on('change', function() {
        var value = this.value;

        $('#divisionselect').find('label').each(function () {
            // Remove all checked fields
            $(this).find('input').prop("checked", false);


            if ($(this).data('orgid') == value) {
                $(this).css({'color':'black'});
                $(this).find('input').removeAttr("disabled");
            } else {
                $(this).css({'color':'lightgrey'});
                $(this).find('input').attr("disabled", true);
            }

            // console.log(this.value);
        });
    });




});