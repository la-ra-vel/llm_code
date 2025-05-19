$(document).ready(function () {
    // Set up AJAX with CSRF token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Update the Select2 dropdown with the new country data
    var client = JSON.parse(document.querySelector('.customData').getAttribute('data-Client'));
    var court_category = JSON.parse(document.querySelector('.customData').getAttribute('data-CourtCategory'));
    var all_court_address = JSON.parse(document.querySelector('.customData').getAttribute('data-all_court_address'));
    var case_court_address = JSON.parse(document.querySelector('.customData').getAttribute('data-case_court_address'));

    // console.log(court_category)
    if (client != null) {
        var clientDropdown = $('#searchClient');

        // Clear previous options
        clientDropdown.empty();
        var full_name = client.fname + ' ' + client.lname;
        // Append new option and set it as selected
        var newOption = new Option(full_name, client.id, true, true);
        clientDropdown.append(newOption).trigger('change');
        $("#client_mobile").val(client.mobile);
    }

    if (court_category != null) {
        var courtCategoryDropdown = $('#searchCourtCategory');

        // Clear previous options
        courtCategoryDropdown.empty();
        // Append new option and set it as selected
        var newOption = new Option(court_category.name, client.id, true, true);
        courtCategoryDropdown.append(newOption).trigger('change');
        $("#client_mobile").val(client.mobile);
    }

    if (case_court_address !== null) {
        // Select the <select> element
        var selectElement = $("#case_court_address");

        // Clear any existing options
        selectElement.empty();
        $.each(all_court_address, function (j, val) {
            var selected = '';
            if (val.court_name == case_court_address) {
                selected = 'selected';
            }
            $("#case_court_address").append('<option value="' + val.court_name + '" ' + selected + '> ' + val.court_name + '</option>');
        });

        // Enable the <select> element
        selectElement.prop('disabled', false);
    }

    // search client ....
    $("#searchClient").select2({
        placeholder: 'Search Client',
        ajax: {
            url: '/search/clients',
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
                            return { id: item.id, text: item.name + ' ( ' + item.id + ' ) ', mobile: item.mobile };
                        })
                    };
                } else {
                    // console.error("Unexpected data format", data);
                    return {
                        results: []
                    };
                }
            },
            cache: true
        },
        minimumInputLength: 1 // Minimum characters to start search
    }).on('select2:select', function (e) {
        // console.log(e.params.data)
        var mobile = e.params.data.mobile;
        $("#client_mobile").val(mobile)
    });



});
