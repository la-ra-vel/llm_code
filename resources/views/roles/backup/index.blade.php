@extends("layout.layout")
@section("style")
<link href="{{asset('css/jquery.dataTables.min.css')}}" rel="stylesheet">

<style>
/* Style for pagination buttons */
.dataTables_wrapper .dataTables_paginate .paginate_button {
    padding: 0.3em 0.6em;
    margin-left: 2px;
    border-radius: 50%;
    border: 1px solid #ddd;
    color: #333;
    background-color: #fff;
    transition: background-color 0.3s, color 0.3s;
}

.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background-color: #f1f1f1;
    color: #000;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background-color: #ff4b5c;
    color: #fff !important;
    border: 1px solid transparent;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
    color: #ccc !important;
}

.dataTables_wrapper .dataTables_paginate .previous::before,
.dataTables_wrapper .dataTables_paginate .next::before {
    content: '';
    display: inline-block;
    width: 10px;
    height: 10px;
    border-top: 2px solid #333;
    border-right: 2px solid #333;
    transform: rotate(135deg);
    margin-right: 5px;
}

.dataTables_wrapper .dataTables_paginate .next::before {
    transform: rotate(-45deg);
    margin-left: 5px;
}

/* Hide text in previous and next buttons */
.dataTables_wrapper .dataTables_paginate .previous,
.dataTables_wrapper .dataTables_paginate .next {
    text-indent: -9999px; /* Hide the text */
    width: 30px; /* Adjust width if necessary */
    height: 30px; /* Adjust height if necessary */
    line-height: 30px; /* Center the arrows vertically */
    display: flex;
    justify-content: center;
    align-items: center;
}

</style>
@endsection
@section("content")

<div class="row page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active"><a href="javascript:void(0)">User Management</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0)">Roles</a></li>
    </ol>
</div>
<!-- row -->

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Roles List</h4>
                <h4>
                <a class="btn btn-rounded btn-info" href="{{route('role.create')}}"><span class="btn-icon-start text-info"><i class="fa fa-plus color-info"></i>
                </span>Add</a>
                </h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <!-- <table id="example3" class="display users-table"> -->
                    <table class="roles-table" style="min-width: 845px; width: 100%;">

                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('custom-script')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{asset('js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('js/datatables.init.js')}}"></script>
<script src="{{asset('js/custom_files/roles.js')}}"></script>
@endpush
