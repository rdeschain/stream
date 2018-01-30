$('#userSelect').click(function () {

    //build user list only if liveChatID is defined
    if ($("#liveChatID").val()) {

        $('#select-status').html(' (refreshing options)');

        $.ajax({
            type: 'GET',
            url: 'list/' + $("#liveChatID").val() + '/' + $("#broadcastID").val() + '?type=names',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }, success: function (result) {

                $('#dropdown-menu').html(result.response.messages);

                $('#select-status').html('');
            }
        });
    }
});

//load chats from selected user
$(document.body).on('click', '.dropdown-menu a', function () {

    $('#select-text').html($(this).text());
    $('#userMessages').html('<i>pulling chat data...</i>');

    $.ajax({
        type: 'GET',
        url: 'list/' + $("#liveChatID").val() + '/' + $("#broadcastID").val() + '?type=name&author=' + encodeURIComponent($(this).text()),
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, success: function (result) {

            $('#userMessages').html(result.response.messages);
        }
    });

});