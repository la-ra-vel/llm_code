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
        var data = JSON.parse(RowData);

        var url = $(this).attr('data-URL');

        var userData = {
            "city_id": data.city.id,
            "city_name": data.city.name,
            "court_categoryID": data.category.id,
            "court_category_name": data.category.name,
            "location": data.location,
            "court_name": data.court_name,
            "court_room_no": data.court_room_no,
            "description": data.description
        };
        console.log(userData)
        // Update form action dynamically
        $('#courtForm').attr('action', url); // Replace '/your-dynamic-action-url' with your dynamic URL
        $('#courtForm').attr('method', 'PUT');

        // Set values to form inputs using jQuery
        $('#courtForm').find('input, select').each(function () {
            var fieldName = $(this).attr('name');
            if (userData[fieldName] !== undefined) {
                if ($(this).is('select')) {
                    $(this).val(userData[fieldName]).trigger('change'); // Trigger change event for select dropdowns

                } else {
                    $(this).val(userData[fieldName]);
                }
            }
        });

        renderDropdowns(userData,'#searchCity');
        renderDropdowns(userData,'#searchCourtCategory');
        $('.courtSubmitBtn').text('Update');
        $('html, body').animate({
            scrollTop: 0
        }, 500); // 500 milliseconds for smooth scroll

    })

    function renderDropdowns(userData,selector) {
        var dropdown = $(selector);

        // Clear previous options
        dropdown.empty();

        // Determine the correct values based on the selector
        var newOption;
        if (selector === '#searchCity') {
            newOption = new Option(userData.city_name, userData.city_id, true, true);
        } else if (selector === '#searchCourtCategory') {
            newOption = new Option(userData.court_category_name, userData.court_categoryID, true, true);
        }

        // Append the new option and set it as selected
        dropdown.append(newOption).trigger('change');

        // Update button text
        $('.stateSubmitBtn').text('Update');
    }

    // Search City
    initializeSelect2('#searchCity', '/search/city', 'Search City');
    initializeSelect2('#searchCourtCategory', '/search/court/category', 'Search Court Category');


    /// SUbmit Add Brand Form ....
    $(document).on('submit', '#courtForm', function (e) {
        e.preventDefault();

        let formData = new FormData($('#courtForm')[0]);
        let method = $("#courtForm").attr('method');
        if (method == 'PUT') {
            formData.append('_method', 'PUT');
        }
        let btn = $('.courtSubmitBtn');
        let btnVal = $('.courtSubmitBtn').text();
        let url = $("#courtForm").attr('action');
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

                    var link = '/courts';
                    var currentForm = '.renderCourtForm';
                    reloadFormComponent(link, currentForm);

                    $('#courtsTable').DataTable().ajax.reload();

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

    /// Add New Court Category
    $(document).on('click','#addNewCourtCategory',function(e){
        e.preventDefault();

        const modal = $("#courtCategoryModal");
        $(modal).modal('show');
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
                    $('#courtCategoryForm')[0].reset();
                    swalWithBootstrapButtons.fire(
                        'Done!',
                        resp.message,
                        'success'
                    )
                    var userData = {
                        "court_categoryID": resp.saveData.id,
                        "court_category_name": resp.saveData.name
                    };
                    renderDropdowns(userData,'#searchCourtCategory');
                    $("#courtCategoryModal").modal('hide');

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
                $('#courtCategoryForm')[0].reset();
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
        var link = '/courts';
        var currentForm = '.renderCourtForm';
        reloadFormComponent(link, currentForm);
    })


});
