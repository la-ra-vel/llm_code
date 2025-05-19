$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on('submit', '#emailVerifyForm', function (e) {
        e.preventDefault();

        let formData = new FormData($('#emailVerifyForm')[0]);
        let btn = $('.verifyEmailBtn');
        let btnVal = $('.verifyEmailBtn').text();
        let url = $("#emailVerifyForm").attr('action');
        let creating = ' verify email...';

        $.ajax({
            type: 'POST',
            url: url,
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function () {

                addContent(btn, creating);
            }, success: function (resp) {
                console.log(resp)
                removeContent(btn, btnVal);
                if (resp.success) {
                    toastr.success(resp.message);


                } else {

                    toastr.warning(resp.message);
                }
            }, error: function (xhr, textStatus, errorThrown) {
                // console.log(xhr.responseJSON)
                removeContent(btn, btnVal)
                toastr.error(xhr.responseText);

                return false;
            }
        });
    });
});
