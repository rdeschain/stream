function userdropdown(){
    $('#userSelect').click(function () {

        //build user list only if liveChatID is defined
        if ($("#liveChatID").val()) {

            $('#select-status').html(' (refreshing data)');

            $.ajax({
                type: 'GET',
                url: 'list/' + $("#liveChatID").val() + '?type=names',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }, success: function (result) {

                    $('#dropdown-menu').html(result.response.messages);
                    $('.dropdown').addClass('open');
                    $('#select-status').html('');
                }
            });
        }
    });

    //close dropdown
    $('#dropdown-menu').click(function () {

        $('.dropdown').removeClass('open');
    })
}
