@extends("layout.layout")
@section("style")
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<link href="{{asset('css/jquery.dataTables.min.css')}}" rel="stylesheet">
<link href="{{asset('css/select2.min.css')}}" rel="stylesheet">
<!-- <link href="{{asset('css/bootstrap-material-datetimepicker.css')}}" rel="stylesheet"> -->
<link href="{{asset('css/default_date_picker.css')}}" rel="stylesheet">
<link href="{{asset('css/default.date.css')}}" rel="stylesheet">
<!-- <style>
    .nav-tabs .nav-link {
        color: white;
        background-color: #E03A00;
        border: 1px solid transparent;
        border-top-left-radius: .25rem;
        border-top-right-radius: .25rem;
        margin-right: 5px;
    }
    .nav-tabs .nav-link.active {
        color: #E03A00;
        background-color: white;
        border-color: #ddd #ddd #fff;
    }
    .tab-pane {
        border: 1px solid #ddd;
        border-top: none;
        padding: 15px;
    }
    .tab-content > .tab-pane:not(.active) {
        display: none;
    }
</style> -->
<style>
    font {
        color: red;
    }
</style>
<style>
    .select2-container--default .select2-selection--multiple {
        border-radius: 0 !important;
    }

    .select2-container--default .select2-selection--single {
        border-radius: 0 !important;
    }

    .select2-container--default .select2-dropdown {
        border-radius: 0 !important;
    }

    hr.dotted {
        border: none;
        border-top: 1px dotted #F93D0F;
        /* You can change the color and width as needed */
    }

    .arrow-button {
        background-color: #007bff;
        /* Button background color */
        color: white;
        /* Text color */
        border: none;
        /* Remove default button border */
        padding: 10px 40px;
        /* Adjusted padding for button */
        font-size: 16px;
        /* Text size */
        cursor: pointer;
        /* Cursor style on hover */
        outline: none;
        /* Remove default focus outline */
        position: relative;
        /* Relative positioning for icon */
        display: inline-flex;
        /* Ensure inline-flex for proper alignment */
        align-items: center;
        /* Center items vertically */
    }

    .arrow-button::after {
        content: '\203A';
        /* Unicode for right arrow character */
        font-size: 20px;
        /* Icon size */
        margin-left: 10px;
        /* Space between text and arrow */
    }

    .left-arrow-button {
        background-color: #007bff;
        /* Button background color */
        color: white;
        /* Text color */
        border: none;
        /* Remove default button border */
        padding: 10px 40px;
        /* Adjusted padding for button */
        font-size: 16px;
        /* Text size */
        cursor: pointer;
        /* Cursor style on hover */
        outline: none;
        /* Remove default focus outline */
        position: relative;
        /* Relative positioning for icon */
        display: inline-flex;
        /* Ensure inline-flex for proper alignment */
        align-items: center;
        /* Center items vertically */
    }

    .left-arrow-button::before {
        content: '\2039';
        /* Unicode for left arrow character */
        font-size: 20px;
        /* Icon size */
        margin-right: 10px;
        /* Space between left arrow and text */
    }

    .table th {
        text-align: center;
        /* Center text horizontally */
        vertical-align: middle !important;
        /* Center text vertically */
    }

    .custom-tab-1 .nav-tabs .nav-link {
        border-radius: 0;
        border-color: #dee2e6 #dee2e6 transparent;
        transition: color 0.3s ease, border-color 0.3s ease;
    }

    .custom-tab-1 .nav-tabs .nav-link.active {
        color: #495057;
        background-color: #fff;
        border-color: #dee2e6 #dee2e6 transparent;
        border-bottom: 1px solid #fff;
    }

    .custom-tab-1 .nav-tabs .nav-link.disabled {
        color: #6c757d;
        pointer-events: none;
        cursor: not-allowed;
    }

    .custom-tab-1 .tab-content {
        padding: 15px;
        border: 1px solid #dee2e6;
        border-top: none;
        border-radius: 0.25rem;
        background-color: #fff;
    }
</style>
@endsection
@section("content")

<div class="row page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active"><a href="javascript:void(0)">Cases</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0)">Create Case</a></li>
    </ol>
</div>
<!-- row -->

<div class="row">
    <div class="col-xl-12 col-lg-12">
        @include('flash_messages')

        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Create Case</h4>
            </div>
            <div class="card-body">
                <!-- Nav tabs -->
                <div class="custom-tab-1">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="courtDetails-tab" data-bs-toggle="tab" href="#courtDetails"
                                role="tab" aria-controls="courtDetails" aria-selected="true">Court Details</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="feeDetails-tab" data-bs-toggle="tab" href="#feeDetails" role="tab"
                                aria-controls="feeDetails" aria-selected="false">Fee Details</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="noteDetails-tab" data-bs-toggle="tab" href="#noteDetails" role="tab"
                                aria-controls="noteDetails" aria-selected="false">Note Details</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="paymentDetails-tab" data-bs-toggle="tab" href="#paymentDetails"
                                role="tab" aria-controls="paymentDetails" aria-selected="false">Payment Details</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="documents-tab" data-bs-toggle="tab" href="#documents" role="tab"
                                aria-controls="documents" aria-selected="false">Documents</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="courtDetails" role="tabpanel"
                            aria-labelledby="courtDetails-tab">
                            @include('case.tabs.court_details')
                        </div>
                        <div class="tab-pane fade" id="feeDetails" role="tabpanel" aria-labelledby="feeDetails-tab">
                            @include('case.tabs.fee_details')
                        </div>
                        <div class="tab-pane fade" id="noteDetails" role="tabpanel" aria-labelledby="noteDetails-tab">
                            @include('case.tabs.note_details')
                        </div>
                        <div class="tab-pane fade" id="paymentDetails" role="tabpanel"
                            aria-labelledby="paymentDetails-tab">
                            @include('case.tabs.payment_details')
                        </div>
                        <div class="tab-pane fade" id="documents" role="tabpanel" aria-labelledby="documents-tab">
                            @include('case.tabs.documents')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('custom-script')
@include('case.scripts.script')
<script src="{{asset('js/custom_files/create_case.js')}}"></script>
<!-- <script>
    $(document).ready(function() {
        $('.next-tab').click(function() {
            var nextTab = $(this).data('next');
            var currentTab = $(this).closest('.tab-pane');
            if (currentTab.find('input:invalid').length == 0) {
                $(nextTab).tab('show');
            } else {
                currentTab.find('input:invalid').first().focus();
            }
        });
        $('form').on('submit', function(event) {
            var form = this;
            if ($(form).find('input:invalid').length > 0) {
                event.preventDefault();
                $(form).find('input:invalid').first().focus();
            }
        });
    });
</script> -->
@endpush
