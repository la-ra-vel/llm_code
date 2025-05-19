var isLoading = false;

/////////// Delete global function ////////////////
window.deleteData = function (url, btn, btnVal, creating) {

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
                        console.log(response)
                        stopLoadingAnimation(btn, btnVal);
                        if (response.status == 200) {
                            $('#dynamic-table').DataTable().ajax.reload();
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
                        console.log(xhr.status)
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
window.updateStatus = function (ID, status, url) {
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
                    $('#dynamic-table').DataTable().ajax.reload();
                }
            }, error: function (xhr, status, error) {
                console.log(xhr.status)
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
    $(selector).select2({
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
                            return { id: item.id, text: item.name };
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
                                console.log('All scripts loaded');


                                // Reinitialize Select2
                                $('.disabling-options').select2();
                                $('.datepicker-default').pickadate();
                                // Initialize Select2 for countries
                                initializeSelect2('#searchCountry', '/search/country', 'Search Country');

                                // Initialize Select2 for states
                                initializeSelect2('#searchState', '/search/state', 'Search State');
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
                }
            }
        },
        error: function (xhr, status, error) {
            console.error('Error:', error);
            alert('Failed to reload form view.');
        }
    });
}

////// Submti Form ...............
window.formSubmit = function(formData,url, btn, btnVal, creating, formLink, currentForm) {
    $.ajax({
        type: 'POST',
        url: url,
        data: formData,
        contentType: false,
        processData: false,
        beforeSend: function() {
            addContent(btn, creating);
        },
        success: function(resp) {
            console.log(resp);
            removeContent(btn, btnVal);
            if (resp.status === 200) {
                swalWithBootstrapButtons.fire(
                    'Done!',
                    resp.message,
                    'success'
                );

                reloadFormComponent(url, currentForm);

                $('#dynamic-table').DataTable().ajax.reload();
            } else if (resp.status === 400) {
                $.each(resp.errors, function(key, value) {
                    var message = value;
                    var title = 'Validation Error';
                    var position = 'toast-top-right';
                    warningNotify(message, title, position);
                });
            } else if (resp.status === 422) {
                // Handle other statuses if needed
            }
        },
        error: function(xhr, textStatus, errorThrown) {
            console.log(xhr.responseJSON);
            removeContent(btn, btnVal);
            swalWithBootstrapButtons.fire(
                'ERROR!',
                xhr.responseJSON.message,
                'error'
            );
            return false;
        }
    });
}
