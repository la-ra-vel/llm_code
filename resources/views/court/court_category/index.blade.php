@extends("layout.layout")

@section("content")
<style>
    body {
        color: #000000 !important;
    }
</style>
<div class="row page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active"><a href="javascript:void(0)">Court</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0)">Court Category</a></li>
    </ol>
</div>
<!-- row -->

<div class="row">
    <div class="col-xl-12 col-lg-12">
        @include('flash_messages')
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Add Court Category</h4>
            </div>
            <div class="card-body renderCourtCategoryForm">

                @include('court.court_category.load_court_category_form')
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-12 col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Court Category List</h4>
            </div>
            <div class="card-body">
                @php
                $headers = ['Sr.No', 'Court Category','Status','Added By', 'Action'];
                $columns = [
                ['data' => 'counter', 'name' => 'counter'],
                ['data' => 'name', 'name' => 'name'],
                ['data' => 'status', 'name' => 'status'],
                ['data' => 'addedBy', 'name' => 'addedBy'],
                ['data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false]
                ];
                @endphp

                <x-custom-table :tableID="'courtCategoryTable'" :headers="$headers" ajaxUrl="{{ route('court.category.list') }}" :columns="$columns" />
            </div>
        </div>

    </div>
</div>

@endsection

@push('custom-script')
@include('common.script')
<script src="{{asset('js/custom_files/court_category.js')}}"></script>
@endpush
