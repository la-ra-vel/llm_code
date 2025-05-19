$(document).ready(function () {
    // Set up AJAX with CSRF token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    /////////// Delete Document Details \\\\\\\\\\\\\\\\\\\\\\\
    $(document).on('click', '.deleteDocumentDetails', function (e) {
        e.preventDefault();
        let url = $(this).attr('data-URL');
        let tableID = $(this).attr('data-tableID');

        let btn = $(this);
        let btnVal = $(this).text();
        let creating = ' Deleting...';
        deleteData(url, btn, btnVal, creating, tableID)
    });

    // Update Document Details Data....
    $(document).on('click', '.updateDocumentDetails', function (e) {
        e.preventDefault();

        var RowData = $(this).attr('data-RowData');
        var user = JSON.parse(RowData);

        var url = $(this).attr('data-URL');

        var userData = {
            "document_name": user.document_name
        };

        // Update form action dynamically
        $('#documentsDetailsForm').attr('action', url); // Replace '/your-dynamic-action-url' with your dynamic URL


        // Set values to form inputs using jQuery
        $('#documentsDetailsForm').find('input, select').each(function () {
            var fieldName = $(this).attr('name');
            if (userData[fieldName] !== undefined) {
                if ($(this).is('select')) {
                    $(this).val(userData[fieldName]).trigger('change'); // Trigger change event for select dropdowns
                } else {
                    $(this).val(userData[fieldName]);
                }
            }
        });

        $('.caseDocumentSubmitBtn').text('Update');
        $('html, body').animate({
            scrollTop: 0
        }, 500); // 500 milliseconds for smooth scroll

    })

    $(document).on('click', '.openDocument', function(e) {
        e.preventDefault(); // Prevent default link behavior

        // Get the document link
        var documentLink = $(this).attr("href");

        // Check if documentLink is not null or undefined
        if (documentLink) {
            // Open the document in a new window with specified features
            window.open(encodeURI(documentLink), "_blank", "scrollbars=1,resizable=1,height=500,width=500");
        } else {
            console.error('No document link found');
        }
    });

});
