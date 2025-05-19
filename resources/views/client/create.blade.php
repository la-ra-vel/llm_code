@extends("layout.layout")

@section("content")
<style>
    body {
        color: #000000 !important;
    }
</style>
<div class="row page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active"><a href="javascript:void(0)">Clients</a></li>
        <!-- <li class="breadcrumb-item"><a href="javascript:void(0)">Create Client</a></li> -->
    </ol>
</div>
<!-- row -->

<div class="row">
    <div class="col-xl-12 col-lg-12">
        @include('flash_messages')
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Client Create</h4>
            </div>
            <div class="card-body renderClientForm">

                @include('client.load_client_form')
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-12 col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Client List</h4>
            </div>
            <div class="card-body">
                @php
                $headers = ['Sr.No', 'Name', 'Mobile', 'City/Village', 'Registered Date','Status', 'Action'];
                $columns = [
                ['data' => 'counter', 'name' => 'counter'],
                ['data' => 'name', 'name' => 'name'],
                ['data' => 'mobile', 'name' => 'mobile'],
                ['data' => 'city', 'name' => 'city'],
                ['data' => 'registered_date', 'name' => 'registered_date'],
                ['data' => 'status', 'name' => 'status'],
                ['data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false]
                ];
                @endphp

                <x-custom-table :tableID="'clientsTable'" :headers="$headers" ajaxUrl="{{ route('client.list') }}" :columns="$columns" />
            </div>
        </div>

    </div>
</div>

@endsection

@push('custom-script')
@include('common.script')
<script src="{{asset('js/custom_files/client.js')}}"></script>
@endpush
