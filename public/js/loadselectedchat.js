function loadselectedchat() {
    //load chats from selected user
    $(document.body).on('click', '.dropdown-menu a', function () {

        $('#select-text').html($(this).text());
        $('#userMessages').html('<i>pulling chat data...</i>');

        $.ajax({
            type: 'GET',
            url: 'list/' + $("#liveChatID").val() + '?type=name&author=' + encodeURIComponent($(this).text()),
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }, success: function (result) {

                $('#userMessages').html(result.response.messages);

            }
        });

    });

    $(document.body).on('change', '.selectpicker', function () {

        console.log('selected ' + $(this).val() + ' ' + $("select option:selected").text());

        $("#videoID").val($(this).val());

        console.log('videoID set to ' + $("#videoID").val() + ' currentVideoID set to ' + $("#currentVideoID").val());
        $("#liveChatID").val('');

        if($("#videoID").val() != '') {

            //load youtube
            $('#player_frame').css("display", '');
            $('#player').attr('src', 'https://www.youtube.com/embed/' + $("#videoID").val() + '?autoplay=1');

            //prep chat. could have just switched to new stream or this could be the first
            $('#chatStatus').removeClass('chat-status').html('disconnected');
            $('#chatbox').removeClass('chat-offline').addClass('chat-offline').html('waiting to connect...');

            var divx = document.getElementById("chatbox");

            $.ajax({
                type: 'GET',
                url: 'video/' + $("#videoID").val(),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }, success: function (result) {

                    console.log('video/ response ' + $("#videoID").val() + ' ' + JSON.stringify(result));

                    $("#liveChatID").val(result.response.liveChatId);

                    console.log('liveChatID set to: ' + $("#liveChatID").val());

                    $('#chatStatus').addClass('chat-status').html('LIVE');
                    $('#chatbox').removeClass('chat-offline').html('retrieving chat history...');

                    //call this on an interval
                    var allowEndCat = false;
                    var endChat = false;

                    //prevents multiple calls made when user changes stream
                    if ($("#currentVideoID").val() != $("#videoID").val() && $("#currentVideoID").val() != '') {
                        console.log('new stream selected');
                        //endChat = true;
                        $("#currentVideoID").val($("#videoID").val());
                        $('#chatbox').empty();
                        $('#userMessages').empty();
                        $('#select-text').html('Select User');
                        allowEndCat = false;
                        console.log('videoID set to ' + $("#videoID").val() + ' setting currentVideoID set to ' + $("#currentVideoID").val());

                    } else {
                        $("#currentVideoID").val($("#videoID").val());
                        console.log('first stream currentVideoID set to ' + $("#currentVideoID").val());
                    }

                    if($("#liveChatID").val() != '') {

                        (function worker() {

                            //save chats
                            $.ajax({
                                type: 'POST',
                                url: 'list/' + $("#liveChatID").val(),
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function (res) {

                                    console.log('videoID: ' + $("#videoID").val() + ' chat response for liveChatID ' + $("#liveChatID").val() + ' response liveChatId: ' + res.response.liveChatId + ' ');

                                    if (res.response.messages != '' && $("#liveChatID").val() == res.response.liveChatId ) {
                                        allowEndCat = true;
                                        console.log('allowEnd true');
                                    }

                                    if (allowEndCat && res.response.messages == '') {
                                        $('#chatStatus').removeClass('chat-status').html('ended');
                                        endChat = true;
                                    }

                                    if (!endChat && res.response.liveChatId == $("#liveChatID").val()) {
                                        $('#chatbox').empty().append(res.response.messages);
                                    }

                                    //auto scroll chat to bottom
                                    divx.scrollTop = divx.scrollHeight;

                                    //kick off next one. putting here to avoid chance of two running at same time. slow server.
                                    if (!endChat) {
                                        setTimeout(worker, 500);
                                    }

                                }
                            })
                        })();
                    }
                }
            });
        }
    });

}