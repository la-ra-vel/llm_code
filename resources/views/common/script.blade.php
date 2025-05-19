<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{asset('js/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('js/select2.full.min.js')}}"></script>
<script src="{{asset('js/select2-init.js')}}"></script>
<script src="{{asset('js/picker.js')}}"></script>
<script src="{{asset('js/picker.date.js')}}"></script>
<script src="{{asset('js/toastr.min.js')}}"></script>
<script src="{{asset('js/notifications/notifications.js')}}"></script>

<script>
	(function($) {
    "use strict"

    //date picker classic default
    $('.datepicker-default').pickadate({
        max: new Date()
    });

    $('.datepicker-actions').pickadate();


})(jQuery);
	</script>
