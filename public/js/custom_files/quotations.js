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
        let btnVal = $(this).text();
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
            "quotation_no": user.quotation_no,
            "subject": user.subject,
            "client_name": user.client_name,
            "client_mobile": user.client_mobile,
            "client_address": user.client_address
        };

        // Update form action dynamically
        $('#quotationForm').attr('action', url); // Replace '/your-dynamic-action-url' with your dynamic URL
        $('#quotationForm').attr('method', 'PUT');
        // Format the date using moment.js
        if (userData.date) {
            userData.date = moment(userData.date).format('DD MMMM, YYYY');
        }

        // Set values to form inputs using jQuery
        $('#quotationForm').find('input, select').each(function () {
            var fieldName = $(this).attr('name');
            if (userData[fieldName] !== undefined) {
                if ($(this).is('select')) {
                    $(this).val(userData[fieldName]).trigger('change'); // Trigger change event for select dropdowns
                } else {
                    $(this).val(userData[fieldName]);
                }
            }
        });

        $('.QuotationSubmitBtn').text('Update');

        $('html, body').animate({
            scrollTop: 0
        }, 500); // 500 milliseconds for smooth scroll


    })


    /// SUbmit Add Brand Form ....
    $(document).on('submit', '#quotationForm', function (e) {
        e.preventDefault();

        let formData = new FormData($('#quotationForm')[0]);
        let method = $("#quotationForm").attr('method');
        if (method == 'PUT') {
            formData.append('_method', 'PUT');
        }
        let btn = $('.QuotationSubmitBtn');
        let btnVal = $('.QuotationSubmitBtn').text();
        let url = $("#quotationForm").attr('action');
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

                removeContent(btn, btnVal);
                if (resp.status === 200) {
                    swalWithBootstrapButtons.fire(
                        'Done!',
                        resp.message,
                        'success'
                    )

                    var link = '/quotations';
                    var currentForm = '.renderQuotationForm';
                    reloadFormComponent(link, currentForm);

                    $('#quotationsTable').DataTable().ajax.reload();

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

    // Generate Quotation...

    $(document).on('click', '.downloadQuotation', function (e) {
        e.preventDefault();

        var id = $(this).attr('data-ID');
        var quotation_no = $(this).attr('data-QuotationNo');
        var url = $(this).attr('data-URL');
        let btn = $(this);
        let btnVal = $(this).text();
        let creating = ' Generating';
        $.ajax({
            url: url,
            type: 'POST',
            data: {
                id: id
            },
            xhrFields: {
                responseType: 'blob'
            },
            beforeSend: function () {
                // addContent(btn, creating);
                startLoadingAnimation(btn, creating);
            },
            success: function (response) {
                stopLoadingAnimation(btn, btnVal);
                var blob = new Blob([response], { type: 'application/pdf' });
                var fieldName = 'quotation#_' + quotation_no + '.pdf';
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = fieldName;
                link.click();
            },
            error: function (xhr, status, error) {

                stopLoadingAnimation(btn, btnVal);
                toastr.error(xhr.statusText);
            }
        });
    });

    //////////// Quotation Description....
    $(document).on('click', '.quotationDescription', function (e) {
        e.preventDefault();
        $('#quotationDesTable').empty();

        var quotation_id = $(this).attr('data-QuotationID');
        var quotation_no = $(this).attr('data-QuotationNo');
        var quotation_description = $(this).attr('data-RowData');
        quotation_description = JSON.parse(quotation_description);

        var url = $(this).attr('data-URL');

        const modal = $("#quotationDesModal");
        modal.find('.modal-title').text("Quotation# " + quotation_no)

        modal.find('input[name=quotation_id]').val(quotation_id)
        modal.find('input[name=description]').val('')
        modal.find('input[name=amount]').val('')

        modal.find('form').attr('action', url)
        // modal.find('form').attr('method', 'POST');
        displayQuotationDescription(quotation_description);
        modal.modal('show');
    })

    /// SUbmit Quotation Description Form ....
    $(document).on('submit', '#QuotationDesForm', function (e) {
        e.preventDefault();

        let formData = new FormData($('#QuotationDesForm')[0]);
        // let method = $("#QuotationDesForm").attr('method');
        // if (method=='PUT') {
        //     formData.append('_method', 'PUT');
        // }
        let btn = $('.QuotationDesSubmitBtn');
        let btnVal = $('.QuotationDesSubmitBtn').text();
        let url = $("#QuotationDesForm").attr('action');
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
                var data = resp.data;

                removeContent(btn, btnVal);
                if (resp.success) {
                    displayQuotationDescription(data);
                    $("#QuotationDesForm")[0].reset();
                    let updatedUrl = url.replace(/\/\d+$/, '');
                    $('#QuotationDesForm').attr('action', updatedUrl);
                    $('#quotationsTable').DataTable().ajax.reload();
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

    function displayQuotationDescription(data) {

        $('#quotationDesTable').empty();
        var counter = 0;
        $.each(data, function (e, item) {
            counter++;
            $("#quotationDesTable").append('<tr>\
                                    <td>'+ counter + '</td>\
                                    <td>'+ item.description + '</td>\
                                    <td>'+ item.amount + '</td>\
                                    <td>'+ moment(item.date).format('DD MMMM, YYYY') + '</td>\
                                    <td>\
                                    <a href="javascript:void(0);" data-URL="' + item.editUrl + '" data-DescriptionData=\'' + JSON.stringify(item) + '\' class="btn btn-xs btn-primary updateQuotationDes"><i class="fas fa-pen"></i></a>\
                                    <a href="javascript:void(0);" data-URL="' + item.deleteUrl + '" data-tableID="clientsTable" class="btn btn-xs btn-danger deleteQuotationDes"><i class="fas fa-trash"></i></a>\
                                    </td>\
                                </tr>');
        })
    }


    // Update updateQuotationDes Data....
    $(document).on('click', '.updateQuotationDes', function (e) {
        e.preventDefault();
        var RowData = $(this).attr('data-DescriptionData');


        var user = JSON.parse(RowData);

        var url = $(this).attr('data-URL');


        var userData = {
            "quotation_id": user.quotation_id,
            "description": user.description,
            "amount": user.amount
        };

        // Update form action dynamically
        $('#QuotationDesForm').attr('action', url); // Replace '/your-dynamic-action-url' with your dynamic URL
        // $('#QuotationDesForm').attr('method', 'PUT');

        // Set values to form inputs using jQuery
        $('#QuotationDesForm').find('input, select').each(function () {
            var fieldName = $(this).attr('name');
            if (userData[fieldName] !== undefined) {
                if ($(this).is('select')) {
                    $(this).val(userData[fieldName]).trigger('change'); // Trigger change event for select dropdowns
                } else {
                    $(this).val(userData[fieldName]);
                }
            }
        });

        $('.QuotationSubmitBtn').text('Update');


    })

    var interval;

    function startLoadingAnimation(deleteBtn, creating) {
        $(deleteBtn).attr("disabled", 'disabled');
        var dots = 0;
        interval = setInterval(function () {
            dots = (dots + 1) % 4;
            var text = creating + '.'.repeat(dots);
            deleteBtn.html('<span class="spinner-border spinner-border-sm"></span> ' + text);
        }, 500); // Adjust the speed of the dots animation here
    }

    function stopLoadingAnimation(deleteBtn, originalText) {
        clearInterval(interval);
        $(deleteBtn).removeAttr("disabled");
        deleteBtn.html(originalText);
    }

    var isLoading = false;
    /////////// Delete Client \\\\\\\\\\\\\\\\\\\\\\\
    $(document).on('click', '.deleteQuotationDes', function (e) {
        e.preventDefault();
        let url = $(this).attr('data-URL');
        let tableID = 'quotationsTable';
        let btn = $(this);
        let btnVal = $(this).text();
        let creating = ' Deleting...';
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        })
        swalWithBootstrapButtons.fire({
            title: 'Are you sure?',
            text: 'Delete Record!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes do it!',
            cancelButtonText: 'No cancel it!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                if (!isLoading) {
                    isLoading = true;
                    $.ajax({
                        method: 'delete',
                        url: url,
                        beforeSend: function () {
                            // addContent(btn, creating);
                            startLoadingAnimation(btn, creating);
                        },

                        success: function (response) {


                            stopLoadingAnimation(btn, btnVal);
                            if (response.success) {
                                $('#quotationsTable').DataTable().ajax.reload();
                                toastr.success(response.message);
                                var data = response.data;

                                displayQuotationDescription(data)
                            }
                        },
                        error: function (xhr, status, error) {

                            stopLoadingAnimation(btn, btnVal);

                            if (xhr.status === 500) {
                                var errorMessage = xhr.responseJSON.message;
                                toastr.error(errorMessage);

                            } else {
                                toastr.error(xhr.statusText);

                            }

                        },
                        complete: function () {
                            isLoading = false;
                        }
                    });
                }
            } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
            ) {
                swalWithBootstrapButtons.fire(
                    'Cancelled',
                    'Data Safed',
                    'error'
                )
            }
        })


    });

    $(document).on('click', '.clear-button', function (e) {
        e.preventDefault();
        var link = '/quotations';
        var currentForm = '.renderQuotationForm';
        reloadFormComponent(link, currentForm);
    })



});
