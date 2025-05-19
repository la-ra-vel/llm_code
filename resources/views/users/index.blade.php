@extends("layout.layout")


@section("content")
<style>
    body {
        color: #000000 !important;
    }
    .code-content {
        display: none; /* Hidden by default */
        overflow: hidden;
        transition: max-height 0.3s ease-out, opacity 0.3s ease-out;
        max-height: 0;
        opacity: 0;
    }

    .code-content.show {
        display: block;
        max-height: 200px; /* Adjust based on expected content size */
        opacity: 1;
    }

    .code-toggle i {
        cursor: pointer;
        font-size: 18px;
        transition: color 0.3s ease;
    }

    .code-toggle i:hover {
        color: #007bff; /* Change to your preferred hover color */
    }
</style>
<div class="row page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active"><a href="javascript:void(0)">User Management</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0)">Users</a></li>
    </ol>
</div>
<!-- row -->

<div class="row">
    <div class="col-xl-12 col-lg-12">
        @include('flash_messages')
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Create User</h4>
            </div>
            <div class="card-body renderUserForm">

                @include('users.load_user_form')
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-12 col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Users List</h4>
            </div>
            <div class="card-body">
                @php
                $headers = ['Sr.No','Image', 'Name', 'Email', 'Mobile', 'Firm Name','Role','Code','Status', 'Action'];
                $columns = [
                ['data' => 'counter', 'name' => 'counter'],
                ['data' => 'logo', 'name' => 'logo'],
                ['data' => 'name', 'name' => 'name'],
                ['data' => 'email', 'name' => 'email'],
                ['data' => 'mobile', 'name' => 'mobile'],
                ['data' => 'firm_name', 'name' => 'firm_name'],
                ['data' => 'role', 'name' => 'role'],
                ['data' => 'code', 'name' => 'code'],
                ['data' => 'status', 'name' => 'status'],
                ['data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false]
                ];
                @endphp

                <x-custom-table :tableID="'usersTable'" :headers="$headers" ajaxUrl="{{ route('users.list') }}" :columns="$columns" />
            </div>
        </div>

    </div>
</div>

@endsection

@push('custom-script')
@include('common.script')
<script src="{{asset('js/custom_files/users.js')}}"></script>
@endpush
