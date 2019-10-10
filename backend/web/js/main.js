$('.eat-apple').click(function () {
    let alert_message = $(this).attr('data-alert');
    let size = $(this).attr('data-size');
    let id = $(this).attr('data-id');
    if (alert_message) {
        alert(alert_message);
    } else {
        for (var i = 1; i <= size; i++) {
            $('#eat-percentage').append('<option value="' + i + '">' + i + '%</option>');
        }
        $('#apple-id').val(id);
        $('#eat-apple-modal')
            .modal('show');
    }

});