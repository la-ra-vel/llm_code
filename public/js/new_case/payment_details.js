$(document).ready(function () {
    // Set up AJAX with CSRF token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    /////////// Delete Payment Details \\\\\\\\\\\\\\\\\\\\\\\
    $(document).on('click', '.deletePaymentDetails', function (e) {
        e.preventDefault();
        let url = $(this).attr('data-URL');
        let tableID = $(this).attr('data-tableID');

        let btn = $(this);
        let btnVal = $(this).text();
        let creating = ' Deleting...';
        deleteData(url, btn, btnVal, creating, tableID)
    });

    // Update Payment Details Data....
    $(document).on('click', '.updatePaymentDetails', function (e) {
        e.preventDefault();

        var RowData = $(this).attr('data-RowData');
        var user = JSON.parse(RowData);

        var url = $(this).attr('data-URL');

        var userData = {
            "amount": user.amount,
            "payment_date": user.payment_date,
            "fee_description_id": user.fee_description_id,
            "payment_mode": user.payment_mode,
            "remarks": user.remarks
        };

        // Update form action dynamically
        $('#paymentDetailsForm').attr('action', url); // Replace '/your-dynamic-action-url' with your dynamic URL

        // Format the date using moment.js
        if (userData.payment_date) {
            userData.payment_date = moment(userData.payment_date).format('DD MMMM, YYYY');
        }
        // Set values to form inputs using jQuery
        $('#paymentDetailsForm').find('input, select').each(function () {
            var fieldName = $(this).attr('name');
            if (userData[fieldName] !== undefined) {
                if ($(this).is('select')) {
                    $(this).val(userData[fieldName]).trigger('change'); // Trigger change event for select dropdowns
                } else {
                    $(this).val(userData[fieldName]);
                }
            }
        });

        $('.casePaymentSubmitBtn').text('Update');


    })

});
