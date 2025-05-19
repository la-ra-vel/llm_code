$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

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

    // Generate Invoice...

    // $(document).on('click','.downloadInvoice', function (e) {
    //     e.preventDefault();

    //     var id = $(this).attr('data-ID');
    //     var invoice_no = $(this).attr('data-caseID');
    //     var counter = $(this).attr('data-counter');
    //     var url = $(this).attr('data-URL');
    //     let btn = $(this);
    //     let btnVal = $(this).text();
    //     let creating = ' Generating';
    //     $.ajax({
    //         url: url,
    //         type: 'POST',
    //         data: {
    //             id: id
    //         },
    //         xhrFields: {
    //             responseType: 'blob'
    //         },
    //         beforeSend: function () {
    //             // addContent(btn, creating);
    //             startLoadingAnimation(btn, creating);
    //         },
    //         success: function (response) {
    //             stopLoadingAnimation(btn, btnVal);
    //             var blob = new Blob([response], { type: 'application/pdf' });
    //             var link = document.createElement('a');
    //             var fieldName = 'case_invoice#_' + invoice_no + '-'+ counter + '.pdf';
    //             link.href = window.URL.createObjectURL(blob);
    //             link.download = fieldName;
    //             link.click();
    //         },
    //         error: function (xhr, status, error) {
    //             // console.log(xhr)
    //             stopLoadingAnimation(btn, btnVal);
    //             toastr.error(xhr.statusText);
    //         }
    //     });
    // });

    $(document).on('click', '.downloadInvoice', function (e) {
        e.preventDefault();

        var id = $(this).attr('data-ID');
        var url = $(this).attr('data-URL');
        var caseId = $(this).attr('data-caseID');
        let btn = $(this);
        let btnVal = $(this).text();
        let creating = ' Generating';

        $.ajax({
            url: url,
            type: 'POST',
            data: { id: id },
            xhrFields: {
                responseType: 'blob' // Expecting binary data
            },
            beforeSend: function () {
                startLoadingAnimation(btn, creating);
            },
            success: function (response, status, xhr) {
                stopLoadingAnimation(btn, btnVal);

                // Extract values from response headers
                var invoice_no = xhr.getResponseHeader('invoice_no');
                // var counter = xhr.getResponseHeader('counter');

                // Create a Blob for the PDF
                var blob = new Blob([response], { type: 'application/pdf' });
                var link = document.createElement('a');

                // Update the file name with dynamic values
                var fieldName = 'case_invoice#_' + caseId + '-' + invoice_no + '.pdf';
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
    /////////// Send Mail \\\\\\\\\\\\\\\\\\\\\\\
    $(document).on('click', '.sendMail', function (e) {
        e.preventDefault();
        let url = $(this).attr('data-URL');
        let email = $(this).attr('data-Email');
        var id = $(this).attr('data-ID');
        let btn = $(this);
        let btnVal = $(this).text();
        let creating = ' Sending email...';
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        })
        swalWithBootstrapButtons.fire({
            title: 'Are you sure?',
            text: 'Email Send!',
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
                        method: 'post',
                        url: url,
                        data: { email: email, id: id },
                        beforeSend: function () {
                            // addContent(btn, creating);
                            startLoadingAnimation(btn, creating);
                        },

                        success: function (response) {


                            stopLoadingAnimation(btn, btnVal);
                            if (response.success) {
                                toastr.success(response.message);
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
                    '',
                    'error'
                )
            }
        })


    });



});
