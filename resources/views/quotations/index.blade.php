@extends("layout.layout")

@section("content")
<style>
    body {
        color: #000000 !important;
    }
</style>
<div class="row page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active"><a href="javascript:void(0)">Quotations</a></li>
        <!-- <li class="breadcrumb-item"><a href="javascript:void(0)">Country</a></li> -->
    </ol>
</div>
<!-- row -->

<div class="row">
    <div class="col-xl-12 col-lg-12">
        @include('flash_messages')
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Quotation Create</h4>
            </div>
            <div class="card-body renderQuotationForm">

                @include('quotations.load_quotation_form')

            </div>
        </div>
    </div>
</div>
<div class="row">
@include('quotations.quotation_des_modal')
    <div class="col-xl-12 col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Quotation List</h4>
            </div>
            <div class="card-body">
                @php
                $headers = ['Sr.No', 'Date/Time','Client Name','Mobile','Address','Subject','Quotation', 'Status','Action'];
                $columns = [

                ['data' => 'quotation_no', 'name' => 'quotation_no'],
                ['data' => 'date_time', 'name' => 'date_time'],
                ['data' => 'client_name', 'name' => 'client_name'],
                ['data' => 'client_mobile', 'name' => 'client_mobile'],
                ['data' => 'client_address', 'name' => 'client_address'],
                ['data' => 'subject', 'name' => 'subject'],
                ['data' => 'quotation', 'name' => 'quotation'],
                ['data' => 'status', 'name' => 'status'],
                ['data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false]
                ];
                @endphp

                <x-custom-table :tableID="'quotationsTable'" :headers="$headers" ajaxUrl="{{ route('quotation.list') }}" :columns="$columns" />
            </div>
        </div>

    </div>
</div>

@endsection

@push('custom-script')
@include('common.script')
<script src="{{asset('js/custom_files/quotations.js')}}"></script>
@endpush
