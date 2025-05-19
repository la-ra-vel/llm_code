$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });



    // Search Country
    initializeSelect2('#searchCountry', '/search/country', 'Search Country');
    // $('#searchCountry').select2({
    //     placeholder: 'Search Country',
    //     ajax: {
    //         url: '/search/country', // Replace with your endpoint URL
    //         method: 'GET',
    //         dataType: 'json',
    //         delay: 250,
    //         data: function (params) {
    //             return {
    //                 q: params.term // search term
    //             };
    //         },
    //         processResults: function (data) {

    //             if (data && data.results && Array.isArray(data.results)) {
    //                 // Access the 'results' key of the data
    //                 return {
    //                     results: data.results.map(function (item) {
    //                         return { id: item.id, text: item.name };
    //                     })
    //                 };
    //             } else {
    //                 // If data format is unexpected
    //                 console.error("Unexpected data format", data);
    //                 return {
    //                     results: []
    //                 };
    //             }
    //         },
    //         cache: true
    //     },
    //     minimumInputLength: 1 // Minimum characters to start search
    // });

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

        var url = $(this).attr('data-URL');

        var userData = {
            "name": user.name,
            "country_id": user.country.id,
            "country_name": user.country.name
        };

        // Update form action dynamically
        $('#stateForm').attr('action', url); // Replace '/your-dynamic-action-url' with your dynamic URL


        // Set values to form inputs using jQuery
        $('#stateForm').find('input, select').each(function () {
            var fieldName = $(this).attr('name');
            if (userData[fieldName] !== undefined) {
                if ($(this).is('select')) {
                    $(this).val(userData[fieldName]).trigger('change'); // Trigger change event for select dropdowns
                } else {
                    $(this).val(userData[fieldName]);
                }
            }
        });

        // Update the Select2 dropdown with the new country data
        var countryDropdown = $('#searchCountry');

        // Clear previous options
        countryDropdown.empty();

        // Append new option and set it as selected
        var newOption = new Option(userData.country_name, userData.country_id, true, true);
        countryDropdown.append(newOption).trigger('change');

        $('.stateSubmitBtn').text('Update');
        $('html, body').animate({
            scrollTop: 0
        }, 500); // 500 milliseconds for smooth scroll
    })


    /// SUbmit Add Brand Form ....
    $(document).on('submit', '#stateForm', function (e) {
        e.preventDefault();

        let formData = new FormData($('#stateForm')[0]);
        let btn = $('.stateSubmitBtn');
        let btnVal = $('.stateSubmitBtn').text();
        let url = $("#stateForm").attr('action');
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

                    var link = '/states';
                    var currentForm = '.renderStateForm';
                    reloadFormComponent(link, currentForm);

                    $('#stateTable').DataTable().ajax.reload();

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
        var link = '/states';
        var currentForm = '.renderStateForm';
        reloadFormComponent(link, currentForm);
    })

});
