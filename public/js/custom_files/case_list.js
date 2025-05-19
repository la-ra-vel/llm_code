$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var isLoading = false;

    // Update Status ....
    // $(document).on('click', '.updateStatus', function (e) {
    //     e.preventDefault();
    //     var ID = $(this).attr('data-ID');
    //     let tableID = $(this).attr('data-tableID');
    //     var status = $(this).attr('data-Status');
    //     var url = $(this).attr('data-URL');

    //     // updateStatus(ID, status, url, tableID)
    //     const swalWithBootstrapButtons = Swal.mixin({
    //         customClass: {
    //             confirmButton: 'btn btn-success',
    //             cancelButton: 'btn btn-danger'
    //         },
    //         buttonsStyling: false
    //     })
    //     if (!isLoading) {
    //         isLoading = true;
    //         $.ajax({
    //             type: 'POST',
    //             url: url,
    //             data: { ID: ID, status: status },
    //             success: function (resp) {
    //                 if (resp.status == 200) {

    //                     var message = resp.message;
    //                     var title = 'Suceess';
    //                     var position = 'toast-top-full-width';
    //                     successNotify(message, title, position)
    //                     $('#' + tableID).DataTable().ajax.reload();
    //                 }
    //             }, error: function (xhr, status, error) {

    //                 if (xhr.status === 500) {
    //                     var errorMessage = xhr.responseJSON.message;
    //                     swalWithBootstrapButtons.fire(
    //                         'Data',
    //                         errorMessage,
    //                         'error'
    //                     )
    //                 } else {
    //                     swalWithBootstrapButtons.fire(
    //                         'Error!',
    //                         xhr.statusText,
    //                         'error'
    //                     )
    //                 }

    //             },
    //             complete: function () {
    //                 isLoading = false;
    //             }
    //         })
    //     }
    // })



    $(document).on('click', '.updateStatus', function (e) {
        e.preventDefault();
        var ID = $(this).attr('data-ID');
        let tableID = $(this).attr('data-tableID');
        var status = $(this).attr('data-Status');
        var url = $(this).attr('data-URL');

        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        });

        if (!isLoading) {
            isLoading = true;

            // Check if the case is being reopened
            if (status === 'open') {
                // Directly update status without checks
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: { ID: ID, status: status },
                    success: function (resp) {
                        var message = resp.message;
                        var title = 'Success';
                        successNotify(message, title, 'toast-top-full-width');
                        $('#' + tableID).DataTable().ajax.reload();
                    },
                    error: function (xhr, status, error) {
                        swalWithBootstrapButtons.fire(
                            'Error!',
                            xhr.statusText,
                            'error'
                        );
                    },
                    complete: function () {
                        isLoading = false;
                    }
                });
            } else {
                // Handle closing the case with payment check
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: { ID: ID, status: status },
                    success: function (resp) {
                        if (resp.status == 200) {
                            var message = resp.message;
                            successNotify(message, 'Success', 'toast-top-full-width');
                            $('#' + tableID).DataTable().ajax.reload();
                        } else if (resp.court_details) {
                            swalWithBootstrapButtons.fire({
                                title: 'Pending Payment',
                                text: resp.message,
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Yes, close the case!',
                                cancelButtonText: 'No, cancel!',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $.ajax({
                                        type: 'POST',
                                        url: url,
                                        data: { ID: ID, status: status, skipPending: true },
                                        success: function (resp) {
                                            successNotify(resp.message, 'Success', 'toast-top-full-width');
                                            $('#' + tableID).DataTable().ajax.reload();
                                        },
                                        error: function (xhr, status, error) {
                                            swalWithBootstrapButtons.fire(
                                                'Error!',
                                                xhr.statusText,
                                                'error'
                                            );
                                        }
                                    });
                                }
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        if (xhr.status === 500) {
                            var errorMessage = xhr.responseJSON.message;
                            swalWithBootstrapButtons.fire(
                                'Data',
                                errorMessage,
                                'error'
                            );
                        } else {
                            swalWithBootstrapButtons.fire(
                                'Error!',
                                xhr.statusText,
                                'error'
                            );
                        }
                    },
                    complete: function () {
                        isLoading = false;
                    }
                });
            }
        }
    });



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

});
