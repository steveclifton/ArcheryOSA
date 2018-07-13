$(document).ready(function() {


    $(document).on('change','#selectround', function (e) {

        var dateSelected = $("option:selected", this).val();
        var eventid = $('input[name="eventid"]').val();


        $.ajax({
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "/admin/geteventdata",
            data: {
                date: dateSelected,
                eventid: eventid,
                type: 'targets'
            }
        }).done(function( json ) {
            if (json.success) {
                $('#adminevents').empty();
                $('#adminevents').append(json.html);
            }

        });


    });



    $(document).on('click','#adminbar', function (e) {

        var type = $(this).attr('data-type');
        var eventid = $(this).attr('data-eventid');



        $.ajax({
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "/admin/geteventdata",
            data: {
                type: type,
                eventid: eventid
            }
        }).done(function( json ) {
            if (json.success) {
                $('#adminevents').empty();
                $('#adminevents').append(json.html);
            }

        });


    });

    $(document).on('click', '#senduseremail', function () {
        var email = $('#email').val();
        var message = $('#useremail').val();
        var eventid = $(this).attr('data-eventid');

        if (message.length > 5) {
            $.ajax({
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "/admin/geteventdata",
                data: {
                    type: 'senduseremail',
                    email: email,
                    message: message,
                    eventid: eventid
                }
            }).done(function( json ) {
                if (json.success) {
                    $('#sendmessage').empty().html('Email Sent!').parent().removeClass('hidden');
                }
                else {
                    $('#sendmessage').empty().html('Message was unable to be sent').parent().removeClass('hidden');
                }

            });

        }
        else {
            $('#sendmessage').empty().html('Please enter a message').parent().removeClass('hidden');
        }
    });






    $(document).on('keyup','#searchuser', function (e) {

        var email = $('#searchuser').val();

        if (email.length > 5) {
            $.ajax({
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "/ajaxsearchuserbyemail",
                data: {
                    email: email
                }
            }).done(function( json ) {
                $('.foundusers').empty();
                $('#searchuserresults').css('display', 'none');

                if (json.success) {

                    json.users.forEach(function(element) {

                        var markup = '<label class="form-check-label" >\n' +
                            '<input class="form-check-input selecteduser" type="checkbox" ' +
                            'data-userid="'+element.userid+'"' +
                            'data-name="'+ element.firstname + ' ' + element.lastname +'" ' +
                            'value="'+element.email+'">\n' +
                            element.email +
                            '</label><br>'
                        $('.foundusers').append(markup);
                    });
                    $('#searchuserresults').css('display', 'block');
                }

            });
        }


    });

    $('#selectarcher').on('change', function (e) {
        var optionSelected = $("option:selected", this).val();
        var eventid = $('input[name="eventid"]').val();

        $.ajax({
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "/ajaxgetusersentryform",
            data: {
                userid: optionSelected,
                eventid: eventid
            }
        }).done(function( json ) {

            if (json.success) {
                $('#formdata').empty();
                $('#formdata').append(json.html);

                if (json.existing) {
                    $('.enterbutton').addClass('hidden');
                    $('.updateremovebutton').removeClass('hidden');
                } else {
                    $('.enterbutton').removeClass('hidden');
                    $('.updateremovebutton').addClass('hidden');
                }
            }

        });

    });


    $(document).on('click', '.selecteduser', function () {

        var userid = $(this).attr('data-userid');
        var fullname = $(this).attr('data-name');

        $('input[name="userid"]').attr('value', userid);
        $('input[name="name"]').val(fullname);
        $('.foundusers').empty();
        $('#searchuserresults').css('display', 'none');
    });

});