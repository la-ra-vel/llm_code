@extends("layout.layout")

@section("content")
<style>
    body {
        color: #000000 !important;
    }
</style>
<div class="row page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active"><a href="javascript:void(0)">Master Data</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0)">State</a></li>
    </ol>
</div>
<!-- row -->

<div class="row">
    <div class="col-xl-12 col-lg-12">
        @include('flash_messages')
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">State Create</h4>
            </div>
            <div class="card-body renderStateForm">

                @include('master_data.state.load_state_form')
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-12 col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">State List</h4>
            </div>
            <div class="card-body">
                @php
                $headers = ['Sr.No','State', 'Country','Status', 'Action'];
                $columns = [
                ['data' => 'counter', 'name' => 'counter'],
                ['data' => 'name', 'name' => 'name'],
                ['data' => 'country', 'name' => 'country'],
                ['data' => 'status', 'name' => 'status'],
                ['data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false]
                ];
                @endphp

                <x-custom-table :tableID="'stateTable'" :headers="$headers" ajaxUrl="{{ route('state.list') }}" :columns="$columns" />
            </div>
        </div>

    </div>
</div>

@endsection

@push('custom-script')
@include('common.script')
<script src="{{asset('js/custom_files/state.js')}}"></script>
@endpush
