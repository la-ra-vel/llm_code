@extends("layout.layout")

@section("content")
<style>
    body {
        color: #000000 !important;
    }
</style>
<div class="row page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active"><a href="javascript:void(0)">Invoice</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0)">List</a></li>
    </ol>
</div>
<!-- row -->

<div class="row">
    <div class="col-xl-12 col-lg-12">
        <div class="card">
            <!-- <div class="card-header">
                <h4 class="card-title">Case List List</h4>
            </div> -->
            <div class="card-body">
                @php
                $headers = ['Sr.No','CaseID', 'Client Name','Mobile','Total Amount','Paid Amount','Remaining Amount',
                'Invoice', 'Action'];
                $columns = [
                ['data' => 'counter', 'name' => 'counter'],
                ['data' => 'caseID', 'name' => 'caseID'],
                ['data' => 'client_name', 'name' => 'client_name'],
                ['data' => 'mobile', 'name' => 'mobile'],
                ['data' => 'total_fee_amount', 'name' => 'total_fee_amount'],
                ['data' => 'total_payment_amount', 'name' => 'total_payment_amount'],
                ['data' => 'remaining_amount', 'name' => 'remaining_amount'],
                ['data' => 'invoice', 'name' => 'invoice'],
                ['data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false]
                ];
                @endphp

                <x-custom-table :tableID="'invoiceTable'" :headers="$headers" ajaxUrl="{{ route('invoice.list') }}"
                    :columns="$columns" />
            </div>
        </div>

    </div>
</div>

@endsection

@push('custom-script')
@include('common.script')
<script src="{{asset('js/custom_files/invoice.js')}}"></script>
@endpush
