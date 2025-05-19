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
        <li class="breadcrumb-item"><a href="javascript:void(0)">Courts</a></li>
    </ol>
</div>
<!-- row -->

<div class="row">
    <div class="col-xl-12 col-lg-12">
        @include('flash_messages')
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Add Court</h4>
            </div>
            <div class="card-body renderCourtForm">

                @include('court.courts.load_court_form')
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-12 col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Court List</h4>
            </div>
            <div class="card-body">
                @php
                $headers = ['Sr.No', 'City','Court Category','Court','Location','Court Room #','Description','Status','Added By', 'Action'];
                $columns = [
                ['data' => 'counter', 'name' => 'counter'],
                ['data' => 'city', 'name' => 'city'],
                ['data' => 'court_category', 'name' => 'court_category'],
                ['data' => 'court_name', 'name' => 'court_name'],
                ['data' => 'location', 'name' => 'location'],
                ['data' => 'court_room_no', 'name' => 'court_room_no'],
                ['data' => 'description', 'name' => 'description'],
                ['data' => 'status', 'name' => 'status'],
                ['data' => 'addedBy', 'name' => 'addedBy'],
                ['data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false]
                ];
                @endphp

                <x-custom-table :tableID="'courtsTable'" :headers="$headers" ajaxUrl="{{ route('courts.list') }}" :columns="$columns" />
            </div>
        </div>

    </div>
</div>

@endsection

@push('custom-script')
@include('common.script')
<script src="{{asset('js/custom_files/courts.js')}}"></script>
@endpush
