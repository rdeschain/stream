function loadDropdown(){

    $('#stream-select').prop("disabled", true);
    $('#stream-select').html('<option selected disabled>loading streams...</option>');

    $.ajax({
        type: 'GET',
        url: 'video/list?q=' + encodeURIComponent($('#stream-search').val()),
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, success: function (result) {

            $('#stream-select').html(result.response.messages);
            $('#stream-select').prop("disabled", false);
        }
    });
}
