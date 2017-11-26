$(document).ready(function() {


    $('table.resultstables').DataTable({
        "paging": false,
        "info": false,
        "searching": false
    });



    if (typeof collapse_siderbar !== 'undefined') {
        if (collapse_siderbar) {
            $('.sidebar-mini').addClass('sidebar-collapse');
        }
    }

    $('.distance').on('keyup', function () {
        var formtype = $(this).data('formtype');

        var userhash = $(this).data('userrow' + formtype);

        var inputs = $("input[data-userrow"+ formtype + "='" + userhash + "']");

        var totalscore = '';
        inputs.each(function () {

            if (this.value !== '') {
                if (!isNaN(Number(this.value))) {
                    if (totalscore === '') {
                        totalscore = 0;
                    }
                    totalscore += parseInt(this.value);
                } else {
                    $(this).css('border', 'solid 2px #FF0000');
                    $(this).addClass('scoringError');
                }
            }
        });

        $("input[data-id='total-" + userhash + "']").val(totalscore);

    });

    $('#scoringform').submit(function (e) {
        var inputs = $(".scoringError");

        if ( inputs.length > 0) {

            e.preventDefault();
            alert('Please check invalid scores and try again');
        }
    });

    $('#scoringformmobile').submit(function (e) {
        var inputs = $(".scoringError");

        if ( inputs.length > 0) {

            e.preventDefault();
            alert('Please check invalid scores and try again');
        }
    });
    
    $('.readmore').click(function () {
        $(this).remove();
        $('.moreschedule').removeClass('hidden');
    });

    $('.addmoredetails').click(function () {

        var dataid = $(this).data('row');
        var inputs = $("input[data-id='" + dataid + "']");
        var thishtml =  $(this).html();
        if ( thishtml == 'Close more details' ) {
            $(this).html('Add more details');
        } else {
            $(this).html('Close more details');
        }
        inputs.each(function() {
            if ($(this).hasClass('hidden')) {
                $(this).removeClass('hidden');
            } else {
                $(this).addClass('hidden')
            }

        });

    });

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

    var clubvalue = $('#userclubvalue').val();
    $('#clubselect option[value=' + clubvalue +']').attr('selected','selected');

    var divisionvalue = $('#userdivisionvalue').val();
    $('#divisionselect option[value=' + divisionvalue +']').attr('selected','selected');

    if ($('#eventtypevalue').val() != 1) {
        $('#maxweeklyinput').addClass('hidden');
    }

    $('#eventtypeid').on('change', function (e) {
        var optionSelected = $("option:selected", this).val();
        $('#eventtypevalue').val(optionSelected);
        if (optionSelected != 1) {
            $('#maxweeklyinput').addClass('hidden');
        } else {
            $('#maxweeklyinput').removeClass('hidden');
        }
    });


    $('.week').on('change', function (e) {
        var optionSelected = $("option:selected", this).val();
        var url = getPathFromUrl(window.location.href);
        window.location.replace(url + '?week=' + optionSelected);


    });

    function getPathFromUrl(url) {
        return url.split("?")[0];
    }





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

    $(".processleaguebtn").click(function(){
        event.stopPropagation();

        if (!confirm("Are you sure you want to process the event??")) {
            event.preventDefault();
        }
    });


    $("a#deleteBtn").click(function(){
        event.stopPropagation();

        if (!confirm("Are you sure you want to delete?")) {
            event.preventDefault();
        }
    });

    // $("#deleteBtn").click(function(){
    //     event.stopPropagation();
    //
    //     if (!confirm("Are you sure you want to remove your entry to this event?")) {
    //         event.preventDefault();
    //     }
    // });

    $("a#deleteUserRelation").click(function(){
        event.stopPropagation();

        if (!confirm("Do you want to delete?")) {
            event.preventDefault();
        }
    });

    $('#showmoreentries').click(function () {
        $('.item').removeClass('hidden');
        $('.showmore').empty();
    });



});
