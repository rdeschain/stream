function postchatmessage() {

    $("#postChatInput").keyup(function(event){
        if(event.keyCode == 13){

            if($("#liveChatID").val()) {

                var postMessage = $("#postChatInput").val();

                console.log('post message to live stream');
                $("#postChatInput").addClass('post-progress').val('posting message: "' + postMessage + '"').prop("disabled", true);

                $.ajax({
                    type: 'POST',
                    url: 'chat/' + $("#liveChatID").val(),
                    data: {message: postMessage},
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }, success: function (result) {

                        $("#postChatInput").removeClass('post-progress').val('').prop("disabled", false);
                    }
                });

            } else {
                console.log('liveChatID not defined');
            }
        }
    });

}