function signInCallback(authResult) {

    //spinner
    $('#signinSpinner').addClass('fa-spin');

    if (authResult['code']) {

        $.ajax({
            type: 'POST',
            url: 'callback',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (result) {

                //only call if chat id is returned
                if (result.status != 'error') {

                    //disable button if everything ok
                    $('#signinButton').prop("disabled", true);
                    $('#signinButton').html("Signed in with Google");
                    $('#signinSpinner').removeClass('fa fa-refresh fa-spin');

                    //enable user input to post to stream chat
                    $('#postChatInput').prop("disabled", false).prop("placeholder", 'type and press enter to post message...');

                    //jump user to input box
                    window.location = '#postChatInput';

                } else {

                    $('#signinSpinner').removeClass('fa-spin');
                    $("#alert").addClass('alert alert-warning').html(result.response.message).fadeTo(3500, 500).slideUp(500, function () {
                        $("#alert").slideUp(500);
                    });
                }
            },
            data: {'code': authResult['code']}
        });
    } else {

        // There was an error.
        $("#alert").addClass('alert alert-warning').html('There was an issue with authorization. Please try again.').fadeTo(3500, 500).slideUp(500, function () {
            $("#alert").slideUp(500);
        });
        $('#signinSpinner').removeClass('fa-spin');
    }
}