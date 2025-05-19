$(document).ready(function () {
    // Set up AJAX with CSRF token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    /////////// Delete Action Details \\\\\\\\\\\\\\\\\\\\\\\
    $(document).on('click', '.deleteActionDetails', function (e) {
        e.preventDefault();
        let url = $(this).attr('data-URL');
        let tableID = $(this).attr('data-tableID');

        let btn = $(this);
        let btnVal = $(this).text();
        let creating = ' Deleting...';
        deleteData(url, btn, btnVal, creating, tableID)
    });


    // Update Action Details Data....
    $(document).on('click', '.updateActionDetails', function (e) {
        e.preventDefault();

        var RowData = $(this).attr('data-RowData');
        var user = JSON.parse(RowData);

        var url = $(this).attr('data-URL');

        var userData = {
            "note": user.note,
            "hearing_date": user.hearing_date
        };

        // Update form action dynamically
        $('#actionDetailsForm').attr('action', url); // Replace '/your-dynamic-action-url' with your dynamic URL

        // Format the date using moment.js
        if (userData.hearing_date) {
            userData.hearing_date = moment(userData.hearing_date).format('DD MMMM, YYYY');
        }
        // Set values to form inputs using jQuery
        $('#actionDetailsForm').find('input, select').each(function () {
            var fieldName = $(this).attr('name');
            if (userData[fieldName] !== undefined) {
                if ($(this).is('select')) {
                    $(this).val(userData[fieldName]).trigger('change'); // Trigger change event for select dropdowns
                } else {
                    $(this).val(userData[fieldName]);
                }
            }
        });

        $('.caseActionSubmitBtn').text('Update');

        $('html, body').animate({
            scrollTop: 0
        }, 500); // 500 milliseconds for smooth scroll
    })

});
