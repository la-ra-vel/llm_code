@extends("layout.layout")
@section("style")
<link href="{{asset('css/jquery.dataTables.min.css')}}" rel="stylesheet">
<link href="{{asset('css/default_date_picker.css')}}" rel="stylesheet">
<link href="{{asset('css/default.date.css')}}" rel="stylesheet">

@endsection
@section("content")

<div class="row page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active"><a href="javascript:void(0)">User Management</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0)">Create User</a></li>
    </ol>
</div>
<!-- row -->

<div class="row">
    <div class="col-xl-12 col-lg-12">
    @include('flash_messages')
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">User Form</h4>
            </div>
            <div class="card-body">
                <div class="basic-form">
                    <form action="{{route('user.store')}}" method="post">@csrf

                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" name="name" placeholder="Name">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" placeholder="Email">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="text" class="form-control" name="mobile" placeholder="mobile">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Date of Birth</label>
                                <input name="dob" class="datepicker-default form-control form-control-sm rounded-0"
                                    id="datepicker">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Current Address</label>
                                <input type="text" class="form-control" name="current_address" placeholder="Current Address">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Permanent Address</label>
                                <input type="text" class="form-control" name="permanent_address" placeholder="Permanent Address">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" placeholder="Password">
                            </div>

                        </div>

                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('custom-script')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{asset('js/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('js/picker.js')}}"></script>
<script src="{{asset('js/picker.date.js')}}"></script>
<script src="{{asset('js/custom_files/users.js')}}"></script>
<script>
	(function($) {
    "use strict"

    //date picker classic default
    $('.datepicker-default').pickadate();

})(jQuery);
	</script>
@endpush
