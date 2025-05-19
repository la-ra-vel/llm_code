$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });



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


        // Update form action dynamically
        $('#roleForm').attr('action', url);

        // Get the user permissions
        var permissions = user.group_permissions.map(p => p.module_id); // Assuming user.group_permissions contains objects with module_id

        // Find the select element with the appropriate name
        var selectElement = $('#roleForm').find('select[name="txtaccess[]"]');

        // Iterate over the options and set the selected ones based on data-moduleID
        selectElement.find('option').each(function () {
            var option = $(this);
            var dataModuleID = option.attr('data-moduleID');

            // Check if the current option's data-moduleID is in the permissions array
            if (permissions.includes(parseInt(dataModuleID))) {
                option.prop('selected', true); // Select the option
            } else {
                option.prop('selected', false); // Deselect the option
            }
        });

        // Trigger change event to update UI elements or plugins
        selectElement.trigger('change');

        // Check if the values are correctly set
        var selectedValues = selectElement.val();
        console.log("Selected values after setting:", selectedValues);

        // For plugins like Select2 or others, you might need additional code to refresh the display
        if (selectElement.hasClass('select2')) {
            selectElement.select2('destroy').select2(); // Reinitialize Select2
            selectElement.trigger('change'); // Trigger change after reinitialization
        }

        // Set the value for the role_name input field
        $('#role_name').val(user.name);

        $('.roleSubmitBtn').text('Update');
        $('html, body').animate({
            scrollTop: 0
        }, 500); // 500 milliseconds for smooth scroll
    });


    /// SUbmit Add Brand Form ....
    $(document).on('submit', '#roleForm', function (e) {
        e.preventDefault();

        let formData = new FormData($('#roleForm')[0]);
        let btn = $('.roleSubmitBtn');
        let btnVal = $('.roleSubmitBtn').text();
        let url = $("#roleForm").attr('action');
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

                    var link = '/roles';
                    var currentForm = '.renderRoleForm';
                    reloadFormComponent(link, currentForm);

                    $('#rolesTable').DataTable().ajax.reload();

                } else if (resp.status === 400) {

                    $.each(resp.errors, function (key, value) {
                        var message = value;
                        var title = 'Validation Error';
                        var position = 'toast-top-right';
                        warningNotify(message, title, position)
                    });
                }
            }, error: function (xhr, textStatus, errorThrown) {

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
        var link = '/roles';
        var currentForm = '.renderRoleForm';
        reloadFormComponent(link, currentForm);
    })

});
