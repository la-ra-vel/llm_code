$(document).ready(function () {
    // Set up AJAX with CSRF token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    /////////// Delete Fee Details \\\\\\\\\\\\\\\\\\\\\\\
    $(document).on('click', '.deleteFeeDetails', function (e) {
        e.preventDefault();
        let url = $(this).attr('data-URL');
        let tableID = $(this).attr('data-tableID');

        let btn = $(this);
        let btnVal = '<i class="fas fa-trash"></i>' + $(this).text();
        let creating = ' Deleting...';
        deleteData(url, btn, btnVal, creating, tableID)
        
    });

    // Update Fee Details Data....
    $(document).on('click', '.updateFeeDetails', function (e) {
        e.preventDefault();

        var RowData = $(this).attr('data-RowData');
        var user = JSON.parse(RowData);

        var url = $(this).attr('data-URL');

        var userData = {
            "fee_description_id": user.fee_description_id,
            "amount": user.amount,
            "remarks": user.remarks
        };

        // Update form action dynamically
        $('#feeDetailsForm').attr('action', url); // Replace '/your-dynamic-action-url' with your dynamic URL

        // Format the date using moment.js
        // if (userData.visiting_date) {
        //     userData.visiting_date = moment(userData.visiting_date).format('DD MMMM, YYYY');
        // }
        // Set values to form inputs using jQuery
        $('#feeDetailsForm').find('input, select').each(function () {
            var fieldName = $(this).attr('name');
            if (userData[fieldName] !== undefined) {
                if ($(this).is('select')) {
                    $(this).val(userData[fieldName]).trigger('change'); // Trigger change event for select dropdowns
                } else {
                    $(this).val(userData[fieldName]);
                }
            }
        });

        $('.caseFeeSubmitBtn').text('Update');
        $('html, body').animate({
            scrollTop: 0
        }, 500); // 500 milliseconds for smooth scroll

    })

});
