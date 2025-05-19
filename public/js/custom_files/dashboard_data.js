$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Search Court Category
    // initializeSelect2('#searchCourtCategory', '/search/court/category', 'Search By Court Category');
    // Search Court Address
    // initializeSelect2('#searchCourtAddress', '/search/court/address', 'Search By Court Address');
    // Search CaseID
    initializeSelect2('#searchCaseID', '/search/court/caseID', 'Search By CaseID');


    var select2Instance = initializeSelect2('#searchCourtCategory', '/search/court/category', 'Search Court Category');

    var secondSelect2 = $('#case_court_address').select2({
        placeholder: 'Select Court Address',
        data: [] // Initially empty
    }).prop('disabled', true);


    select2Instance.on('select2:select', function (e) {
        console.log("working for sub")
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


    /// Search Result base on selected value
    var table = $('#casesTable').DataTable({
        // processing: true,
        serverSide: true,
        ajax: {
            url: "/court-custom-search",
            data: function (d) {
                d.court_category = $('#searchCourtCategory').val();
                d.court_address = $('#case_court_address').val();
                // d.case_id = $('#searchCaseID').val();
                d.custom_search = $('#custom_search').val();
                d.default_cases = $('#default_cases').val();

                if ($('#open_cases_link').data('clicked')) {
                    d.open_cases_link = $('#open_cases_link').attr('data-open_cases_link');
                } else if ($('#close_cases_link').data('clicked')) {
                    d.close_cases_link = $('#close_cases_link').attr('data-close_cases_link');
                } else if ($('#upcoming_actions_link').data('clicked')) {
                    d.upcoming_actions_link = $('#upcoming_actions_link').attr('data-upcoming_actions_link');
                } else if ($('#pending_today_link').data('clicked')) {
                    d.pending_today_link = $('#pending_today_link').attr('data-pending_today_link');
                } else if ($('#upcoming_one_week_link').data('clicked')) {
                    d.upcoming_one_week_link = $('#upcoming_one_week_link').attr('data-upcoming_one_week_link');
                } else if ($('#upcoming_one_month_link').data('clicked')) {
                    d.upcoming_one_month_link = $('#upcoming_one_month_link').attr('data-upcoming_one_month_link');
                }
            }
        },
        columns: [
            { data: 'caseID', name: 'caseID' },
            { data: 'client_name', name: 'client_name' },
            { data: 'client_mobile', name: 'client_mobile' },
            { data: 'opponent_name', name: 'opponent_name' },
            { data: 'courtCategory', name: 'courtCategory', defaultContent: '' },
            { data: 'case_court_address', name: 'case_court_address', defaultContent: '' }
        ],
        lengthMenu: [[10, 25, 50, 100, 500, -1], [10, 25, 50, 100, 500, "All"]],
        dom: '<"row d-flex align-items-center"<"col-sm-12 col-md-4 mt-4 text-md-start d-flex align-items-center"l><"col-sm-12 col-md-8 d-flex justify-content-end align-items-center"<"mt-4 me-3"f>B>>rtip',
        // buttons: [
        //     'copy', 'csv', 'excel', 'pdf', 'print'
        // ],
        buttons: [
            {
                extend: 'copy',
                exportOptions: {
                    columns: ':not(:last-child)'
                }
            },
            {
                extend: 'csv',
                exportOptions: {
                    columns: ':not(:last-child)'
                }
            },
            {
                extend: 'excel',
                exportOptions: {
                    columns: ':not(:last-child)'
                }
            },
            {
                extend: 'pdf',
                exportOptions: {
                    columns: ':not(:last-child)'
                }
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: ':not(:last-child)'
                }
            }
        ],

        drawCallback: function (settings) {
            var api = this.api();
            var pagination = $(api.table().container()).find('.dataTables_paginate');
            var currentPage = api.page.info().page;
            var totalPages = api.page.info().pages;

            // Clear previous pagination
            pagination.html('');

            // Add previous button
            pagination.append('<a class="paginate_button previous ' + (currentPage == 0 ? 'disabled' : '') + '"><<</a>');

            // Add page buttons
            for (var i = 0; i < totalPages; i++) {
                var active = currentPage == i ? 'current' : '';
                pagination.append('<a class="paginate_button ' + active + '">' + (i + 1) + '</a>');
            }

            // Add next button
            pagination.append('<a class="paginate_button next ' + (currentPage == totalPages - 1 ? 'disabled' : '') + '">>></a>');

            // Attach event handlers for pagination buttons
            pagination.find('.paginate_button').not('.disabled').on('click', function () {
                if ($(this).hasClass('previous')) {
                    api.page('previous').draw('page');
                } else if ($(this).hasClass('next')) {
                    api.page('next').draw('page');
                } else {
                    api.page(parseInt($(this).text()) - 1).draw('page');
                }
            });
        },
        initComplete: function () {
            $('#casesTable').on('processing.dt', function (e, settings, processing) {
                if (processing) {
                    $('.custom-spinner-container').show();
                } else {
                    $('.custom-spinner-container').hide();
                }
            });
        }
    });

    $(document).on('submit','#customCourtDataSearchForm', function (e) {
        e.preventDefault();
        resetClickedLinks();
        table.draw();
        scrollDownFun();
    });

    $('.clear-button').on('click', function (e) {
        e.preventDefault();
        resetClickedLinks();
        clearCustomSearch();
        table.draw();
        scrollDownFun();

    });

    // Add click event listener for the <a> tag
    $(document).on('click', '#open_cases_link', function () {
        clearCustomSearch();
        resetClickedLinks();
        $(this).data('clicked', true);
        table.draw();
        scrollDownFun();
    });

    $(document).on('click', '#close_cases_link', function () {
        clearCustomSearch();
        resetClickedLinks();
        $(this).data('clicked', true);
        table.draw();
        scrollDownFun();
    });

    $(document).on('click', '#upcoming_actions_link', function () {
        clearCustomSearch();
        resetClickedLinks();
        $(this).data('clicked', true);
        table.draw();
        scrollDownFun();
    });

    $(document).on('click', '#pending_today_link', function () {
        clearCustomSearch();
        resetClickedLinks();
        $(this).data('clicked', true);
        table.draw();
        scrollDownFun();
    });

    $(document).on('click', '#upcoming_one_week_link', function () {
        clearCustomSearch();
        resetClickedLinks();
        $(this).data('clicked', true);
        table.draw();
        scrollDownFun();
    });

    $(document).on('click', '#upcoming_one_month_link', function () {
        clearCustomSearch();
        resetClickedLinks();
        $(this).data('clicked', true);
        table.draw();
        scrollDownFun();
    });

    function resetClickedLinks() {
        $('#open_cases_link').data('clicked', false);
        $('#close_cases_link').data('clicked', false);
        $('#upcoming_actions_link').data('clicked', false);
        $('#pending_today_link').data('clicked', false);
        $('#upcoming_one_week_link').data('clicked', false);
        $('#upcoming_one_month_link').data('clicked', false);
    }

    function clearCustomSearch() {
        $('#searchCourtCategory').val('');
        $('#case_court_address').val('');
        $('#searchCaseID').val('');
        $('#default_cases').val('');
        $('#custom_search').val('');
        $('#searchCourtCategory').val('').trigger('change');
        $('#case_court_address').val('').trigger('change');


    }

    function scrollDownFun(){
        $('html, body').animate({
            scrollTop: $(document).scrollTop() + 500
        }, 500); // Scrolls down 500 pixels in 500 milliseconds
    }


    /**************************** Get The Widget Values ************************ */

    $.ajax({
        url: '/dashboard-widget-data', // Replace with your endpoint
        method: 'GET',
        success: function (response) {
            if (response.success) {
                animateCounter('open_cases', response.openCases);
                animateCounter('close_cases', response.closeCases);
                animateCounter('upcoming_actions', response.upcomingActions);
                animateCounter('pending_today', response.todayActions);
                animateCounter('upcoming_one_week', response.upcomingOneWeekActions);
                animateCounter('upcoming_one_month', response.upcomingOneMonthActions);


                // Define a mapping of element IDs to the corresponding response data keys
                console.log(response.upcomingActionsIds)
                const dataMap = {
                    'open_cases_link': response.openCasesIds,
                    'close_cases_link': response.closeCasesIds,
                    'upcoming_actions_link': response.upcomingActionsIds,
                    'pending_today_link': response.todayActionsQueryIds,
                    'upcoming_one_week_link': response.upcomingOneWeekActionsIds,
                    'upcoming_one_month_link': response.upcomingOneMonthActionsIds
                };

                // Loop through the mapping and set the custom attributes
                for (let id in dataMap) {

                    let element = document.getElementById(id);
                    if (element) {
                        element.setAttribute(`data-${id}`, dataMap[id]);
                    }
                }

            }
        }
    });

    function animateCounter(elementId, endValue) {
        let element = document.getElementById(elementId);
        let startValue = 0;
        let duration = 2000; // 2 seconds
        let stepTime = Math.abs(Math.floor(duration / endValue));

        if (endValue === 0) {
            element.innerText = 0;
            return;
        }

        let counter = setInterval(function () {
            startValue += 1;
            element.innerText = startValue;

            if (startValue >= endValue) {
                clearInterval(counter);
            }
        }, stepTime);
    }


    // $(document).on('click', '.clear-button', function (e) {
    //     e.preventDefault();
    //     var link = '/dashboard';
    //     var currentForm = '.renderSearchbarForm';
    //     reloadFormComponent(link, currentForm);
    // })




});
