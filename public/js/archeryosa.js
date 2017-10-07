$(document).ready(function() {
    var selectedValueRound = $('#roundsunitvalue').val();
    $('#roundsunit option[value=' + selectedValueRound +']').attr('selected','selected');

    var selectedValueClubCountry = $('#clubscountryvalue').val();
    $('#clubscountry option[value=' + selectedValueClubCountry +']').attr('selected','selected');

    var selectedValueClubOrganisation = $('#organisationvalue').val();
    $('#organisationselect option[value=' + selectedValueClubOrganisation +']').attr('selected','selected');

    var selectedValueDivisionOrganisation = $('#organisationvalue').val();
    $('#organisationselect option[value=' + selectedValueDivisionOrganisation +']').attr('selected','selected');

    var selectedValueOrganisation = $('#organisationvalueeventround').val();
    $('#organisationselecteventround option[value=' + selectedValueOrganisation +']').attr('selected','selected');

    var selectedValueRound = $('#roundsvalue').val();
    $('#roundselect option[value=' + selectedValueRound +']').attr('selected','selected');

    var selectedEventType = $('#eventtypevalue').val();
    $('#eventtypeid option[value=' + selectedEventType +']').attr('selected','selected');

    var selectedValueEventStatus = $('#eventstatus').val();
    $('#eventstatusselect option[value=' + selectedValueEventStatus +']').attr('selected','selected');

    var dateEventRoundValue = $('#dateeventroundval').val();
    $('#dateselect option[value=' + dateEventRoundValue +']').attr('selected','selected');

    var userOrganisationValue = $('#organisationvalue').val();
    $('#organisationselecteventround option[value=' + userOrganisationValue +']').attr('selected','selected');


    var eventorganisationid = $('#organisationidvalue').val();
    $('#organisationselect option[value=' + eventorganisationid +']').attr('selected','selected');

    // This will work for when you are updating an eventround
    $('#divisionselect').find('label').each(function () {
        if ($(this).data('orgid') != $('#organisationvalueeventround').val()) {
            $(this).css({'color': 'lightgrey'});
            $(this).find('input').attr("disabled", true);
        }
    });

    $('#divisionselectcreate').find('label').each(function () {
        if ($(this).data('orgid') != 0) {
            $(this).css({'color': 'lightgrey'});
            $(this).find('input').attr("disabled", true);
        }
    });

    $('#organisationselecteventround').on('change', function() {
        var value = this.value;
        console.log(value);
        // For update
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
        });

        // For Create
        $('#divisionselectcreate').find('label').each(function () {
            // Remove all checked fields
            $(this).find('input').prop("checked", false);

            if ($(this).data('orgid') == value) {
                $(this).css({'color':'black'});
                $(this).find('input').removeAttr("disabled");
            } else {
                $(this).css({'color':'lightgrey'});
                $(this).find('input').attr("disabled", true);
            }
        });
    });


    $("a#deleteBtn").click(function(){
        event.stopPropagation();

        if (!confirm("Do you want to delete?")) {
            event.preventDefault();
        }



    });



});