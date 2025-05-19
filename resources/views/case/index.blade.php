@extends("layout.layout")

@section("content")

<style>
    body {
        color: #000000 !important;
    }
</style>
<div class="row page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active"><a href="javascript:void(0)">Cases</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0)">Case List</a></li>
    </ol>
</div>
<!-- row -->


<div class="row">
    <div class="col-xl-12 col-lg-12">
    @include('flash_messages')
        <div class="card">

            <div class="card-header">
                <h4 class="card-title">Case List List</h4>
            </div>
            <div class="card-body">
                @php
                $headers = ['Sr.No','Client','CaseID', 'Legal Matter','Opponent Name','Status','CreatedBy', 'Action'];
                $columns = [
                ['data' => 'counter', 'name' => 'counter'],
                ['data' => 'client', 'name' => 'client'],
                ['data' => 'caseID', 'name' => 'caseID'],
                ['data' => 'legal_matter', 'name' => 'legal_matter'],
                ['data' => 'opponentName', 'name' => 'opponentName'],
                ['data' => 'status', 'name' => 'status'],
                ['data' => 'createdBy', 'name' => 'createdBy'],
                ['data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false]
                ];
                @endphp

                <x-custom-table :tableID="'caseTable'" :headers="$headers" ajaxUrl="{{ route('case.list') }}" :columns="$columns" />
            </div>
        </div>

    </div>
</div>

@endsection

@push('custom-script')
@include('common.script')
<script src="{{asset('js/custom_files/case_list.js')}}"></script>
<script type="text/javascript">
    window.setTimeout(function() {
        $(".alert").alert('close');
    }, 10000);
</script>
@endpush
