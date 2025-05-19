$(document).ready(function () {
    let page = 1;
    let loading = false;
    let hasMoreItems = true; // To track if there are more items to load

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on('click', '.updatePassword', function (e) {
        e.preventDefault();
        var userID = $(this).attr('data-UserID');
        var url = $(this).attr('data-URL');
        const modal = $('#updatePasswordModal');
        modal.find('.modal-title').text("Change Password")
        modal.find('input[name=current_password]').val('')
        modal.find('input[name=password]').val('')
        modal.find('input[name=password_confirmation]').val('')
        modal.find('input[name=user_id]').val(userID)

        modal.find('form').attr('action', url)
        modal.find('.updatePasswordBtn').text('Update Password');
        $(modal).modal('show');

    });

    /// Update Password ....
    $(document).on('submit', '#updatePasswordForm', function (e) {
        e.preventDefault();

        let formData = new FormData($('#updatePasswordForm')[0]);
        let btn = $('.updatePasswordBtn');
        let btnVal = $('.updatePasswordBtn').text();
        let url = $("#updatePasswordForm").attr('action');
        let creating = ' Processing...';



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
                    $('#updatePasswordModal').modal('hide');
                    toastr.success(resp.message);

                } else if (resp.status === 400) {

                    $.each(resp.errors, function (key, value) {
                        toastr.warning(value);
                    });
                }
            }, error: function (xhr, textStatus, errorThrown) {

                removeContent(btn, btnVal)
                toastr.warning(xhr.responseJSON.message);

                return false;
            }
        });
    });

    function loadUserActivity(append = false) {
        if (loading || !hasMoreItems) return; // Prevent loading if already loading or no more items
        loading = true;

        $.ajax({
            url: '/user/activity',
            method: 'GET',
            data: { userID: $('#userID').val(), page: page },
            success: function (activities) {

                if (!append) {
                    $('#RecentActivityContent').empty();
                }
                if (activities.length === 0) {
                    hasMoreItems = false; // No more items to load
                    $('#RecentActivityContent').append('<li class="no-more-items">No more Activity</li>');
                    loading = false;
                    return;
                }

                activities.forEach(function (activity) {
                    $('#RecentActivityContent').append('<div class="d-flex recent-activity">\
                                                <span class="me-3 activity">\
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17"\
                                                        viewBox="0 0 17 17">\
                                                        <circle cx="8.5" cy="8.5" r="8.5" fill="#f93a0b" />\
                                                    </svg>\
                                                </span>\
                                                <div class="d-flex align-items-center">\
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="71" height="71"\
                                                        viewBox="0 0 71 71">\
                                                        <g transform="translate(-457 -443)">\
                                                            <rect width="71" height="71" rx="12"\
                                                                transform="translate(457 443)" fill="#c5c5c5" />\
                                                            <g transform="translate(457 443)">\
                                                                <rect data-name="placeholder" width="71" height="71"\
                                                                    rx="12" fill="#2769ee" />\
                                                                <circle data-name="Ellipse 12" cx="18" cy="18" r="18"\
                                                                    transform="translate(15 20)" fill="#fff" />\
                                                                <circle data-name="Ellipse 11" cx="11" cy="11" r="11"\
                                                                    transform="translate(36 15)" fill="#ffe70c"\
                                                                    style="mix-blend-mode: multiply;isolation: isolate" />\
                                                            </g>\
                                                        </g>\
                                                    </svg>\
                                                    <div class="ms-3">\
                                                        <h5 class="mb-1">'+activity.subject+'</h5>\
                                                        <span>'+moment(activity.created_at).fromNow()+'</span>\
                                                    </div>\
                                                </div>\
                                            </div>');
                });
                loading = false;
            }
        });
    }

    $('#RecentActivityContent').on('scroll', function () {
        let scrollTop = $(this).scrollTop();
        let innerHeight = $(this).innerHeight();
        let scrollHeight = this.scrollHeight;

        if (scrollTop + innerHeight >= scrollHeight - 10) { // Adding a small offset
            page++;
            loadUserActivity(true);
        }
    });

    loadUserActivity();


});
