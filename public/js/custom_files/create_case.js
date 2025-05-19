$(document).ready(function () {
    // Set up AJAX with CSRF token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // initializeSelect2('#searchCourtCategory', '/search/court/category', 'Search Court Category').on('select2:select', function (e) {
    //     console.log(e.params.data);

    // });
    var selectedFeeDetails = JSON.parse(document.querySelector('.customData').getAttribute('data-selectedFeeDetails'));

    if (selectedFeeDetails != null) {
        renderSelectedFeeDetails(selectedFeeDetails);
    }


    var select2Instance = initializeSelect2('#searchCourtCategory', '/search/court/category', 'Search Court Category');

    var secondSelect2 = $('#case_court_address').select2({
        placeholder: 'Select Court Address',
        data: [] // Initially empty
    }).prop('disabled', true);

    select2Instance.on('select2:select', function (e) {

        var courtDataArray = e.params.data.data;

        // Map the data to the format expected by Select2
        var newOptions = courtDataArray.map(function (item) {
            return { id: item.court_name, text: item.court_name };
        });

        // Add the default "- Select -" option
        newOptions.unshift({ id: '', text: '- select -' });

        // Update the second Select2 with the new options
        var secondSelect2 = $('#case_court_address');
        secondSelect2.empty(); // Clear current options
        secondSelect2.select2({
            data: newOptions
        });

        // Enable the second Select2
        secondSelect2.prop('disabled', false);

    });

    // Function to switch to the next tab
    function switchToNextTab(currentTabId) {
        const $currentNavLink = $(`a[href="#${currentTabId}"]`);
        const $nextNavLink = $currentNavLink.closest('.nav-item').next('.nav-item').find('a[data-bs-toggle="tab"]');

        if ($nextNavLink.length) {
            console.log("Next tab exists, switching to:", $nextNavLink.attr('href'));

            // Use Bootstrap's Tab class to show the next tab
            const tab = new bootstrap.Tab($nextNavLink[0]);
            tab.show();
        } else {
            console.log("No more tabs to switch to.");
            alert("You have reached the last tab.");
            // window.location = '/create/case'
        }
    }

    // Global variable to track if the pending payment check should be skipped
    let skipPendingPaymentCheck = false;

    // Function to handle form submission
    function handleFormSubmit(event) {
        event.preventDefault(); // Prevent default form submission

        const $form = $(this);
        let actionUrl = $form.attr('action');
        const $submitButton = $form.find('button[type="submit"], input[type="submit"]').filter((_, el) => el === document.activeElement);
        const btnVal = $submitButton.text().trim();
        let creating = ' Processing...';

        // Prepare FormData
        let formData = new FormData($form[0]);
        const currentTabText = $(`a[href="#${$form.closest('.tab-pane').attr('id')}"]`).text().trim();

        // Add the flag to skip the pending payment check if necessary
        if (skipPendingPaymentCheck) {
            formData.append('skipPendingPaymentCheck', true);
        }

        searchClient = $('#searchClient').val();
        searchCourtCategory = $('#searchCourtCategory').val();
        case_court_address = $('#case_court_address').val();
        case_acts = $('#case_acts').val();

        // Perform AJAX request
        $.ajax({
            url: actionUrl,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function () {
                addContent($submitButton, creating);
            },
            success: function (response) {
                removeContent($submitButton, btnVal);

                if (response.court_details) {
                    // If there is a pending payment and skipPendingPaymentCheck is false, ask for confirmation
                    if (!skipPendingPaymentCheck && confirm(`${response.message}`)) {
                        // User confirms to skip pending payment check, set the flag and resubmit
                        skipPendingPaymentCheck = true;
                        $form.submit();
                        return;
                    } else if (!skipPendingPaymentCheck) {
                        // User clicked "No", reset the flag and stop the process
                        skipPendingPaymentCheck = false;
                        return;
                    }
                }

                if (response.success) {
                    $form.find('select').val('').trigger('change');
                    toastr.success(currentTabText + " saved successfully.");

                    var courtDetailsId = response.court_detail_id;
                    var saveDataID = response.saveDataID;
                    var total_fees = response.total_fees;
                    var payment_received = response.payment_received;
                    var pending_payment = response.pending_payment;

                    $('#fee_description_id').select2({
                        placeholder: 'Select Fee Description',
                        data: [] // Initially empty
                    });

                    var courtDataArray = response.selectedFeeDetails;
                    renderSelectedFeeDetails(courtDataArray);

                    updateAjaxUrl('fee_detailsTable', courtDetailsId);
                    updateAjaxUrl('action_detailsTable', courtDetailsId);
                    updateAjaxUrl('payment_detailsTable', courtDetailsId);
                    updateAjaxUrl('document_detailsTable', courtDetailsId);

                    $("#total_fees").text(total_fees);
                    $("#payment_received").text(payment_received);
                    $("#pending_payment").text(pending_payment);

                    $('form').each(function () {
                        let $currentForm = $(this);
                        let $hiddenInput = $currentForm.find('input[name="client_case_pid"]');
                        if ($hiddenInput.length) {
                            $hiddenInput.val(courtDetailsId);
                        } else {
                            $currentForm.append(`<input type="hidden" name="client_case_pid" value="${courtDetailsId}">`);
                        }
                    });

                    const currentTabId = $form.closest('.tab-pane').attr('id');

                    if (currentTabId == 'courtDetails') {
                        $('#searchClient').val(searchClient).trigger('change');
                        $('#searchCourtCategory').val(searchCourtCategory).trigger('change');
                        $('#case_court_address').val(case_court_address).trigger('change');
                        $('#case_acts').val(case_acts).trigger('change');

                        let newActionUrl = `${actionUrl.replace(/\/?\d+$/, '')}/${saveDataID}`;
                        $form.attr('action', newActionUrl);
                    } else {
                        let newUrl = actionUrl.replace(/\/\d+$/, '');
                        $form.attr('action', newUrl);
                        $form[0].reset();
                    }
                } else if (response.status === 400) {
                    $.each(response.errors, function (key, value) {
                        toastr.warning(value);
                    });
                } else {
                    toastr.error(response.message);
                }
            },
            error: function (xhr, status, error) {
                removeContent($submitButton, btnVal);
                toastr.warning(xhr.responseJSON.message);
                return false;
            }
        });
    }

    function renderSelectedFeeDetails(courtDataArray) {
        // var secondSelect2 = $('#fee_description_id').select2({
        //     placeholder: 'Select',
        //     data: [] // Initially empty
        // }).prop('disabled', true);


        var secondSelect2 = $('#fee_description_id');
        secondSelect2.empty();

        // Add a default option with empty value and disable it
        secondSelect2.append(new Option('-select-', '', true, true)).prop('disabled', true);

        // Sort the courtDataArray by the fee_description name or id
        courtDataArray.sort(function (a, b) {
            // Sort by fee_description.name
            return a.fee_description.name.localeCompare(b.fee_description.name);
            // If you want to sort by id, uncomment the line below and comment the above line
            // return a.fee_description.id - b.fee_description.id;
        });

        var newOptions = courtDataArray.map(function (item) {
            return { id: item.fee_description.id, text: item.fee_description.name };
        });
        // secondSelect2.select2({ data: newOptions });
        // Re-enable the select element and initialize with new options
        secondSelect2.select2({
            data: newOptions,
            placeholder: '-select-', // Set the placeholder
            allowClear: true          // Allow clearing the selection
        }).prop('disabled', false);
        // Listen for the dropdown opening event and focus the search input

    }

    function updateAjaxUrl(componentId, courtDetailsId) {
        var component = document.getElementById(componentId);
        var ajaxUrlTemplate = component.getAttribute('ajaxUrl');
        var newAjaxUrl = ajaxUrlTemplate.replace(':id', courtDetailsId);
        component.setAttribute('ajaxUrl', newAjaxUrl);

        if (window.dataTables && window.dataTables[componentId]) {
            window.dataTables[componentId].ajax.url(newAjaxUrl).load();
        }
    }

    // Attach form submit event listener using event delegation
    $(document).on('submit', '.tab-pane form', handleFormSubmit);



    // Handle tab click and shown events
    $(document).on('click', 'a[data-bs-toggle="tab"]', function (e) {
        const targetTabPaneId = $(this).attr('href'); // Get the target tab pane ID
        console.log("Tab clicked:", targetTabPaneId);

        // Clear previous tab content and activate the new tab content
        $(targetTabPaneId).addClass('show active').siblings('.tab-pane').removeClass('show active');
    });

    $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
        const targetTabPaneId = $(e.target).attr('href'); // Get the target tab pane ID
        console.log("Tab shown:", targetTabPaneId);

        // Ensure the content is displayed correctly
        $(targetTabPaneId).addClass('show active').siblings('.tab-pane').removeClass('show active');
    });
});
