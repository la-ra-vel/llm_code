@extends("layout.layout")
@section("style")
<link href="{{asset('css/jquery.dataTables.min.css')}}" rel="stylesheet">
<link href="{{asset('css/select2.min.css')}}" rel="stylesheet">


@endsection
@section("content")

<div class="row page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active"><a href="javascript:void(0)">User Management</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0)">Create Role</a></li>
    </ol>
</div>
<!-- row -->

<div class="row">
    <div class="col-xl-12 col-lg-12">
    @if(Session::has('flash_message_error'))
<div class="alert alert-danger">

    <strong> {!! session('flash_message_error') !!} </strong>
</div>

@endif
@if(Session::has('flash_message_success'))
<div class="alert alert-success">

    <strong> {!! session('flash_message_success') !!} </strong>
</div>
@endif

@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Role Form</h4>
            </div>
            <div class="card-body">
                <div class="basic-form">
                    <form action="{{route('role.store')}}" method="post">@csrf

                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Role Name</label>
                                <input type="text" class="form-control" name="name" placeholder="Name">

                                @foreach($groupModule as $key => $data)
                                    <input type="hidden" name="txtModID[<?php echo $key; ?>]" value="<?php echo $data['id']; ?>">
                                        <input type="hidden" name="txtModname[<?php echo $key; ?>]" value="<?php echo $data['module_name']; ?>">
                                        <input type="hidden" name="txtModpage[<?php echo $key; ?>]" value="<?php echo $data['module_page']; ?>">
                                    @endforeach
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Roles</label>
                                <select class="multi-select select2-hidden-accessible" name="txtaccess[]" multiple=""
                                    data-select2-id="3" tabindex="-1" aria-hidden="true">
                                    @foreach($groupModule as $key => $data)
                                    <option value="{{$key}}">{{$data->module_name}}</option>
                                    @endforeach
                                </select>
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
<script src="{{asset('js/custom_files/users.js')}}"></script>
<script src="{{asset('js/select2.full.min.js')}}"></script>
<script src="{{asset('js/select2-init.js')}}"></script>
@endpush
