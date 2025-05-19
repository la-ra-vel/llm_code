$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on('click','#clearCache',function(e){
        e.preventDefault();
        $.ajax({
            type: 'GET',
            url: '/cache-clear',
            success: function (resp) {
                if (resp.success) {
                    toastr.success('cache cleared');
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                toastr.warning(xhr.responseJSON.message);
                return false;
            }
        });
    });

    // Initialize theme based on localStorage
    var storedTheme = localStorage.getItem('theme') || 'light';
    $('html').attr('data-theme-version', storedTheme);
    $('body').attr('data-theme-version', storedTheme);

    //////// Update Theme Mode ....

    $('.dz-theme-mode').click(function () {
        var newMode = ($('#icon-light').is(':visible')) ? 'dark' : 'light';
        console.log((newMode === 'dark') ? 'Light mode is active' : 'Dark mode is active');
        updateThemeMode(newMode);
    });

    function updateThemeMode(mode) {
        $.ajax({
            type: 'POST',
            url: '/update/theme/mode',
            data: { mode: mode },
            success: function (resp) {
                if (resp.success) {
                    // Apply the selected theme
                    $('html').attr('data-theme-version', mode);
                    $('body').attr('data-theme-version', mode);
                    localStorage.setItem('theme', mode);
                    console.log('Switched to ' + mode + ' mode');
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                toastr.warning(xhr.responseJSON.message);
                return false;
            }
        });
    }
});
