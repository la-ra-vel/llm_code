$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on('click', '.code-toggle i', function() {
        var $codeContent = $(this).siblings('.code-content');
        if ($codeContent.hasClass('show')) {
            $codeContent.removeClass('show');
            $(this).removeClass('fa-eye-slash').addClass('fa-eye');
        } else {
            $codeContent.addClass('show');
            $(this).removeClass('fa-eye').addClass('fa-eye-slash');
        }
    });

    // Update Status ....
    $(document).on('click', '.updateStatus', function (e) {
        e.preventDefault();
        var ID = $(this).attr('data-ID');
        let tableID = $(this).attr('data-tableID');
        var status = $(this).attr('data-Status');
        var url = $(this).attr('data-URL');

        updateStatus(ID, status, url,tableID)
    })

    /////////// Delete Client \\\\\\\\\\\\\\\\\\\\\\\
    $(document).on('click', '.delete', function (e) {
        e.preventDefault();
        let url = $(this).attr('data-URL');
        let tableID = $(this).attr('data-tableID');
        let btn = $(this);
        let btnVal = $(this).text();
        let creating = ' Deleting...';
        deleteData(url, btn, btnVal, creating,tableID)


    });

    // Update Client Data....
    $(document).on('click', '.update', function (e) {
        e.preventDefault();
        var logo = $(this).attr('data-Logo');
        var RowData = $(this).attr('data-RowData');
        var user = JSON.parse(RowData);
        // console.log(user.fname)
        var url = $(this).attr('data-URL');

        var userData = {
            "username": user.username,
            "fname": user.fname,
            "lname": user.lname,
            "mobile": user.mobile,
            "email": user.email,
            "address": user.address,
            "firm_name": user.firm_name,
            "group_id": user.group_id,
            "logo": logo
        };

        // Update form action dynamically
        $('#userForm').attr('action', url); // Replace '/your-dynamic-action-url' with your dynamic URL
        $('#userForm').attr('method', 'PUT');
        // Format the date using moment.js

        // Set values to form inputs using jQuery
        $('#userForm').find('input:not([type="file"]), select').each(function () {
            var fieldName = $(this).attr('name');
            var $label = $('label[for="' + $(this).attr('id') + '"]');

            if (fieldName === 'password' || fieldName === 'password_confirmation') {
                $(this).attr('type', 'hidden'); // Hide the input fields
                $label.hide();
            }else{
                if (userData[fieldName] !== undefined) {
                    if ($(this).is('select')) {
                        $(this).val(userData[fieldName]).trigger('change'); // Trigger change event for select dropdowns
                    } else {
                        $(this).val(userData[fieldName]);
                    }
                }
            }

        });
        // Append the logo in the image tag
        $('#profile_picture_preview').attr('src', logo);
        $('.userSubmitBtn').text('Update');
        $('html, body').animate({
            scrollTop: 0
        }, 500); // 500 milliseconds for smooth scroll

    })


    /// SUbmit Add Brand Form ....
    $(document).on('submit', '#userForm', function (e) {
        e.preventDefault();

        let formData = new FormData($('#userForm')[0]);
        let method = $("#userForm").attr('method');
        if (method=='PUT') {
            formData.append('_method', 'PUT');
        }


        let btn = $('.userSubmitBtn');
        let btnVal = $('.userSubmitBtn').text();
        let url = $("#userForm").attr('action');
        let creating = ' Processing...';

        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        })

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
                if (resp.status === 200) {
                    swalWithBootstrapButtons.fire(
                        'Done!',
                        resp.message,
                        'success'
                    )

                    var link = '/users';
                    var currentForm = '.renderUserForm';
                    reloadFormComponent(link, currentForm);

                    $('#usersTable').DataTable().ajax.reload();

                } else if (resp.status === 400) {

                    $.each(resp.errors, function (key, value) {
                        var message = value;
                        var title = 'Validation Error';
                        var position = 'toast-top-right';
                        warningNotify(message, title, position)
                    });
                } else if (resp.status === 422) {

                }
            }, error: function (xhr, textStatus, errorThrown) {
                console.log(xhr.responseJSON)
                removeContent(btn, btnVal)
                swalWithBootstrapButtons.fire(
                    'ERROR!',
                    xhr.responseJSON.message,
                    // xhr.responseText,
                    'error'
                )
                return false;
            }
        });
    });




});
