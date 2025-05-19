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

        updateStatus(ID, status, url,tableID)
    })

    /////////// Delete Client \\\\\\\\\\\\\\\\\\\\\\\
    $(document).on('click', '.delete', function (e) {
        e.preventDefault();
        let url = $(this).attr('data-URL');
        let tableID = $(this).attr('data-tableID');

        let btn = $(this);
        let btnVal = '<i class="fas fa-trash"></i>' + $(this).text();
        let creating = ' Deleting...';
        deleteData(url, btn, btnVal, creating,tableID)


    });

    // Update Client Data....
    $(document).on('click', '.update', function (e) {
        e.preventDefault();
        var RowData = $(this).attr('data-RowData');
        var user = JSON.parse(RowData);

        var url = $(this).attr('data-URL');

        var userData = {
            "name": user.name,
            "code": user.code
        };

        // Update form action dynamically
        $('#courtCategoryForm').attr('action', url); // Replace '/your-dynamic-action-url' with your dynamic URL
        $('#courtCategoryForm').attr('method', 'PUT');

        // Set values to form inputs using jQuery
        $('#courtCategoryForm').find('input, select').each(function () {
            var fieldName = $(this).attr('name');
            if (userData[fieldName] !== undefined) {
                if ($(this).is('select')) {
                    $(this).val(userData[fieldName]).trigger('change'); // Trigger change event for select dropdowns
                } else {
                    $(this).val(userData[fieldName]);
                }
            }
        });

        $('.courtCategorySubmitBtn').text('Update');

        $('html, body').animate({
            scrollTop: 0
        }, 500); // 500 milliseconds for smooth scroll
    })


    /// SUbmit Add Court Category Form ....
    $(document).on('submit', '#courtCategoryForm', function (e) {
        e.preventDefault();

        let formData = new FormData($('#courtCategoryForm')[0]);
        let method = $("#courtCategoryForm").attr('method');
        if (method=='PUT') {
            formData.append('_method', 'PUT');
        }
        let btn = $('.courtCategorySubmitBtn');
        let btnVal = $('.courtCategorySubmitBtn').text();
        let url = $("#courtCategoryForm").attr('action');
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

                    var link = '/court_category';
                    var currentForm = '.renderCourtCategoryForm';
                    reloadFormComponent(link, currentForm);

                    $('#courtCategoryTable').DataTable().ajax.reload();

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

    $(document).on('click', '.clear-button', function (e) {
        e.preventDefault();
        var link = '/court_category';
        var currentForm = '.renderCourtCategoryForm';
        reloadFormComponent(link, currentForm);
    })


});
