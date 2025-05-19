$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    // Update Status ....
    $(document).on('click', '.updateStatus', function (e) {
        e.preventDefault();
        var ID = $(this).attr('data-ID');
        let tableID = $(this).attr('data-tableID');
        var status = $(this).attr('data-Status');
        var url = $(this).attr('data-URL');

        updateStatus(ID, status, url, tableID)
    })

    /////////// Delete Client \\\\\\\\\\\\\\\\\\\\\\\
    $(document).on('click', '.delete', function (e) {
        e.preventDefault();
        let url = $(this).attr('data-URL');
        let tableID = $(this).attr('data-tableID');

        let btn = $(this);
        let btnVal = '<i class="fas fa-trash"></i>' + $(this).text();
        let creating = ' Deleting...';
        deleteData(url, btn, btnVal, creating, tableID)


    });

    // Update Client Data....
    $(document).on('click', '.update', function (e) {
        e.preventDefault();
        var RowData = $(this).attr('data-RowData');
        var user = JSON.parse(RowData);
        console.log(user.fname)
        var url = $(this).attr('data-URL');

        var userData = {
            "title": user.title,
            "fname": user.fname,
            "lname": user.lname,
            "mobile": user.mobile,
            "wp_no": user.wp_no,
            "email": user.email,
            "address": user.address,
            "city": user.city,
            "pincode": user.pincode,
            // "visiting_date": user.visiting_date,
            "gender": user.gender,
            "occupation": user.occupation
        };

        // Update form action dynamically
        $('#clientForm').attr('action', url); // Replace '/your-dynamic-action-url' with your dynamic URL

        // Format the date using moment.js
        if (userData.visiting_date) {
            userData.visiting_date = moment(userData.visiting_date).format('DD MMMM, YYYY');
        }
        // Set values to form inputs using jQuery
        $('#clientForm').find('input, select').each(function () {
            var fieldName = $(this).attr('name');
            if (userData[fieldName] !== undefined) {
                if ($(this).is('select')) {
                    $(this).val(userData[fieldName]).trigger('change'); // Trigger change event for select dropdowns
                } else {
                    $(this).val(userData[fieldName]);
                }
            }
        });

        $('.clientSubmitBtn').text('Update');

        // Scroll to the form container
        $('html, body').animate({
            scrollTop: 0
        }, 500); // 500 milliseconds for smooth scroll

    })


    /// SUbmit Add Brand Form ....
    $(document).on('submit', '#clientForm', function (e) {
        e.preventDefault();

        let formData = new FormData($('#clientForm')[0]);
        let btn = $('.clientSubmitBtn');
        let btnVal = $('.clientSubmitBtn').text();
        let url = $("#clientForm").attr('action');
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
                    // reloadFormView();
                    // $("#clientForm")[0].reset();
                    // reloadFormComponent();
                    var link = '/clients';
                    var currentForm = '.renderClientForm';
                    reloadFormComponent(link, currentForm);

                    $('#clientsTable').DataTable().ajax.reload();

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





    // Function to reload form view
    function reloadFormView() {
        $.ajax({
            url: '/create/client', // Adjust the URL to your form view URL
            type: 'GET',
            success: function (resp) {
                if (resp.html) {
                    $('#clientFormContainer').html(resp.html);
                    // Reinitialize datepicker and other plugins
                    $('.datepicker-default').datepicker();
                    // Reinitialize any other plugins if necessary
                } else {
                    console.error('Error: Response does not contain HTML.');
                    alert('Failed to reload form view.');
                }
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
                alert('Failed to reload form view.');
            }
        });
    }

    $(document).on('click', '.clear-button', function (e) {
        e.preventDefault();
        var link = '/clients';
        var currentForm = '.renderClientForm';
        reloadFormComponent(link, currentForm);
    })

});
