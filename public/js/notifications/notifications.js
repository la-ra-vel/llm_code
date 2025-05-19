window.addContent = function (btn, addBtnText) {
    $(btn).text(addBtnText);
    $(btn).prepend('<i class="fa fa-spinner fa-spin"></i>');
    $(btn).attr("disabled", 'disabled');

}

window.removeContent = function (btn, btnVal) {
    $(btn).text(btnVal);
    $(btn).find(".fa-spinner").remove();
    $(btn).removeAttr("disabled");

}


////// Warning Notification ....
window.warningNotify = function (message, title, position) {

    var duration = '5000';
    toastr.warning(message, title, toastNotification(position, duration));
}
////// success Notification ....
window.successNotify = function (message, title, position) {
    var duration = '1000';
    toastr.success(message, title, toastNotification(position, duration));
}

function toastNotification(position, duration) {
    return {
        positionClass: position,
        timeOut: duration,
        closeButton: true,
        debug: false,
        newestOnTop: true,
        progressBar: true,
        preventDuplicates: true,
        onclick: null,
        showDuration: "1000",
        hideDuration: duration,
        extendedTimeOut: duration,
        showEasing: "swing",
        hideEasing: "linear",
        showMethod: "fadeIn",
        hideMethod: "fadeOut",
        tapToDismiss: false
    };
}

//////////// Delete Spinner Function ..........

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

// function startLoadingAnimation(deleteBtn, creating) {
//     $(deleteBtn).attr("disabled", 'disabled');
//     var dots = 0;
//     var trashGifUrl = "{{ asset('uploads/trash.gif') }}";
//     console.log(trashGifUrl)
//     interval = setInterval(function () {
//         dots = (dots + 1) % 4;
//         var text = creating + '.'.repeat(dots);
//         deleteBtn.html('<img src="'+trashGifUrl+'" style="width: 16px; height: 16px;"> ' + text);
//     }, 500); // Adjust the speed of the dots animation here
// }

// function stopLoadingAnimation(deleteBtn, originalText) {
//     clearInterval(interval);
//     $(deleteBtn).removeAttr("disabled");
//     deleteBtn.html('<i class="fa fa-trash"></i> ' + originalText);
// }

var isLoading = false;

/////////// Delete global function ////////////////
window.deleteData = function (url, btn, btnVal, creating, tableID) {

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
                        // removeContent(btn, btnVal);

                        if (response.calculations === true) {

                            var total_fees = response.total_fees;
                            var payment_received = response.payment_received;
                            var pending_payment = response.pending_payment;
                            $("#total_fees").text(total_fees)
                            $("#payment_received").text(payment_received)
                            $("#pending_payment").text(pending_payment)

                        }

                        stopLoadingAnimation(btn, btnVal);
                        if (response.status == 200) {
                            $('#' + tableID).DataTable().ajax.reload();
                            swalWithBootstrapButtons.fire(
                                'Data',
                                response.message,
                                'success'
                            )
                        } else if (response.status == 422) {
                            swalWithBootstrapButtons.fire(
                                'Data',
                                response.message,
                                'error'
                            )
                        }
                    },
                    error: function (xhr, status, error) {
                        // removeContent(btn, btnVal);
                        stopLoadingAnimation(btn, btnVal);

                        if (xhr.status === 500) {
                            var errorMessage = xhr.responseJSON.message;
                            swalWithBootstrapButtons.fire(
                                'Data',
                                errorMessage,
                                'error'
                            )
                        } else {
                            swalWithBootstrapButtons.fire(
                                'Data',
                                xhr.statusText,
                                'error'
                            )
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
}

///// Update Status
window.updateStatus = function (ID, status, url, tableID) {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    })
    if (!isLoading) {
        isLoading = true;
        $.ajax({
            type: 'POST',
            url: url,
            data: { ID: ID, status: status },
            success: function (resp) {
                if (resp.status == 200) {

                    var message = resp.message;
                    var title = 'Suceess';
                    var position = 'toast-top-full-width';
                    successNotify(message, title, position)
                    $('#' + tableID).DataTable().ajax.reload();
                }
            }, error: function (xhr, status, error) {

                if (xhr.status === 500) {
                    var errorMessage = xhr.responseJSON.message;
                    swalWithBootstrapButtons.fire(
                        'Data',
                        errorMessage,
                        'error'
                    )
                } else {
                    swalWithBootstrapButtons.fire(
                        'Error!',
                        xhr.statusText,
                        'error'
                    )
                }

            },
            complete: function () {
                isLoading = false;
            }
        })
    }
}

///// Load  dynamic form of the current page....

function loadCSS(url) {
    // Check if CSS file is already loaded
    if (!$("link[href='" + url + "']").length) {
        var link = document.createElement('link');
        link.rel = 'stylesheet';
        link.href = url;
        document.head.appendChild(link);
    }
}

// Function to load JavaScript file
function loadScript(url, callback) {
    // Check if script file is already loaded
    if (!$("script[src='" + url + "']").length) {
        var script = document.createElement('script');
        script.src = url;
        script.type = 'text/javascript';
        script.onload = callback;
        document.head.appendChild(script);
    } else {
        // Execute callback if script is already loaded
        if (callback) {
            callback();
        }
    }
}
function initializeSelect2(selector, url, placeholder) {
    var select2Instance = $(selector).select2({
        placeholder: placeholder,
        ajax: {
            url: url,
            method: 'GET',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term // search term
                };
            },
            processResults: function (data) {
                if (data && data.results && Array.isArray(data.results)) {
                    return {
                        results: data.results.map(function (item) {
                            return { id: item.id, text: item.name, data: item.data };
                        })
                    };
                } else {
                    console.error("Unexpected data format", data);
                    return {
                        results: []
                    };
                }
            },
            cache: true
        },
        minimumInputLength: 1 // Minimum characters to start search
    });
    return select2Instance;
}


window.reloadFormComponent = function (url, renderCurrentForm) {

    $.ajax({
        url: url,
        type: 'GET',
        success: function (response) {
            if (response.html) {
                $(renderCurrentForm).html(response.html);

                // Clear previous assets to avoid duplicates
                $('link[href^="css/"]').remove();
                $('script[src^="js/"]').remove();

                // Load new assets
                if (Array.isArray(response.styles)) {
                    response.styles.forEach(function (styleUrl) {
                        loadCSS(styleUrl);
                    });
                }
                if (Array.isArray(response.scripts)) {
                    let scriptsLoaded = 0;
                    response.scripts.forEach(function (scriptUrl) {
                        loadScript(scriptUrl, function () {
                            scriptsLoaded++;
                            if (scriptsLoaded === response.scripts.length) {
                                // All scripts are loaded, perform additional initialization if needed
                                // console.log('All scripts loaded');


                                // Reinitialize Select2
                                $('.disabling-options').select2();
                                $('.datepicker-default').pickadate();
                                // Initialize Select2 for countries
                                initializeSelect2('#searchCountry', '/search/country', 'Search Country');

                                // Initialize Select2 for states
                                initializeSelect2('#searchState', '/search/state', 'Search State');

                                initializeSelect2('#searchCity', '/search/city', 'Search City');
                                initializeSelect2('#searchCourtCategory', '/search/court/category', 'Search Court Category');
                            }
                        });
                    });
                } else {
                    // No scripts to load, perform additional initialization if needed
                    // Reinitialize Select2
                    $('.disabling-options').select2();
                    $('.datepicker-default').pickadate();
                    // Initialize Select2 for countries
                    initializeSelect2('#searchCountry', '/search/country', 'Search Country');

                    // Initialize Select2 for states
                    initializeSelect2('#searchState', '/search/state', 'Search State');
                    initializeSelect2('#searchCity', '/search/city', 'Search City');
                    initializeSelect2('#searchCourtCategory', '/search/court/category', 'Search Court Category');
                }
            }
        },
        error: function (xhr, status, error) {
            console.error('Error:', error);
            alert('Failed to reload form view.');
        }
    });
}
