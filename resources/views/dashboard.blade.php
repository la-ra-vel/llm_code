@extends("layout.layout")

@if($permissionCheck['access'] == 1)

@section("style")
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">

<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.bootstrap4.min.css">
<link href="{{asset('css/select2.min.css')}}" rel="stylesheet">
<link href="{{asset('css/default_date_picker.css')}}" rel="stylesheet">
<link href="{{asset('css/default.date.css')}}" rel="stylesheet">
<link href="{{asset('css/toastr.min.css')}}" rel="stylesheet">
<link href="{{asset('css/custom/yajra_pagination.css')}}" rel="stylesheet">
@endsection
@section("content")
<style>
    body {
        color: #000000 !important;
    }
    
    .container {
        margin-top: 50px;
    }

    .todo-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .todo-header h2 {
        margin: 0;
    }

    .btn-custom {
        background-color: #6c63ff;
        color: white;
        border: none;
        border-radius: 20px;
        padding: 5px 15px;
        cursor: pointer;
    }

    .btn-custom:focus {
        outline: none;
    }

    .btn-group .btn {
        border-radius: 20px;
        margin: 0 5px;
        padding: 5px 15px;
    }

    .btn-group .btn.active {
        background-color: #6c63ff;
        color: white;
    }

    .todo-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px;
        background-color: white;
        border-radius: 10px;
        margin-bottom: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .todo-item span {
        flex-grow: 1;
        margin-left: 10px;
    }

    .todo-item button {
        border: none;
        background-color: #ff6b6b;
        color: white;
        border-radius: 20px;
        padding: 5px 10px;
        cursor: pointer;
    }

    .todo-item button:focus {
        outline: none;
    }

    #todo-list {
        overflow-y: auto;
        padding-right: 15px;
        /* space for the scrollbar */
    }
    .todo-width {
        height: 290px;
    }
    @media (min-width: 1200px) and (max-width: 1399.98px) {
        .todo-width {
            height: 315px;
        }
    }
    /* CSS for the No More Items message */
    .no-more-items {
        text-align: center;
        font-size: 18px;
        color: #999;
        padding: 20px 0;
        opacity: 0;
        /* Start hidden */
        animation: fadeIn 1s forwards;
        /* Apply fade-in animation */
    }

    /* Keyframes for the fade-in effect */
    @keyframes fadeIn {
        0% {
            opacity: 0;
            /* Invisible at start */
            transform: translateY(20px);
            /* Start 20px below */
        }

        100% {
            opacity: 1;
            /* Fully visible */
            transform: translateY(0);
            /* Original position */
        }
    }

    /***** Clear Button  */
    .clear-button {
        background-color: #6c757d;
        border: none;
        color: white;
        padding: 10px 20px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 4px 2px;
        cursor: pointer;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    .clear-button:hover {
        background-color: #5a6268;
    }

    .clear-button i {
        margin-right: 8px;
    }
    .text-white {
        color: #565656 !important;
        text-align: center;
    }
    p {
        text-align: center;
        font-size: 16px !important;
    }
    .force-primary {
        background-color: #F93A0B;
        transition: background-color 0.3s ease;
    }
    .force-primary:hover {
        background-color: #CC2C05;
    }
    .force-active.active {
        background-color: #F93A0B !important;
        transition: background-color 0.3s ease;
    }
    .force-active.active:hover {
        background-color: #CC2C05 !important;
    }
</style>
<div class="row">
    @include('flash_messages')
    <div class="col-xl-8">
        <div class="row">
            <div class="col-xl-12">
                <div>
                    <div>
                        <div class="row">
                            <div class="col-sm-4">
                                <a href="javascript:void(0);" id="open_cases_link">
                                    <x-widget-card :mdClass="'col-xl-12 col-xxl-12 col-lg-12 col-sm-12 shadow'"
                                        :title="'Open Cases'" :id="'open_cases'" :icon="''" :value="'0'"
                                        :customClass="'widget-stat card'" />
                                </a>

                            </div>
                            <div class="col-sm-4">
                                <a href="javascript:void(0);" id="close_cases_link">
                                    <x-widget-card :mdClass="'col-xl-12 col-xxl-12 col-lg-12 col-sm-12 shadow'"
                                        :title="'Close Cases'" :id="'close_cases'" :icon="''" :value="'0'"
                                        :customClass="'widget-stat card'" />
                                </a>
                            </div>
                            <div class="col-sm-4">
                                <a href="javascript:void(0);" id="upcoming_actions_link">
                                    <x-widget-card :mdClass="'col-xl-12 col-xxl-12 col-lg-12 col-sm-12 shadow'"
                                        :title="'Upcoming Actions'" :id="'upcoming_actions'" :icon="''" :value="'0'"
                                        :customClass="'widget-stat card'" />
                                </a>
                            </div>
                            <div class="col-sm-4">
                                <a href="javascript:void(0);" id="pending_today_link">
                                    <x-widget-card :mdClass="'col-xl-12 col-xxl-12 col-lg-12 col-sm-12 shadow'"
                                        :title="'Pending Today'" :id="'pending_today'" :icon="''" :value="'0'"
                                        :customClass="'widget-stat card'" />
                                </a>
                            </div>
                            <div class="col-sm-4">
                                <a href="javascript:void(0);" id="upcoming_one_week_link">
                                    <x-widget-card :mdClass="'col-xl-12 col-xxl-12 col-lg-12 col-sm-12 shadow'"
                                        :title="'Upcoming One Week'" :id="'upcoming_one_week'" :icon="''" :value="'0'"
                                        :customClass="'widget-stat card'" />
                                </a>
                            </div>
                            <div class="col-sm-4">
                                <a href="javascript:void(0);" id="upcoming_one_month_link">
                                    <x-widget-card :mdClass="'col-xl-12 col-xxl-12 col-lg-12 col-sm-12 shadow'"
                                        :title="'Upcoming one Month'" :id="'upcoming_one_month'" :icon="''" :value="'0'"
                                        :customClass="'widget-stat card'" />
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body renderSearchbarForm">
                        @include('dashboard_searchbar')
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="todo-header">
                                <h2>To-Do List</h2>
                                <div>
                                    <button class="btn-custom force-primary" id="get-list">GET LIST</button>
                                    <button class="btn-custom force-primary" id="add-todo">ADD</button>
                                </div>
                            </div>

                            <div>
                                <input type="text" id="description" class="form-control mb-2"
                                    placeholder="Description">

                                <!-- <select class="default-select dashboard-select" id="is_complete">
                                            <option data-display="completed" value="1">Completed</option>
                                            <option value="0">Incomplete</option>

                                        </select> -->
                            </div>
                            <div class="btn-group my-3" role="group" aria-label="Basic example">
                                <button type="button" class="btn btn-secondary filter-button force-active"
                                    data-status="incomplete">INCOMPLETE</button>
                                <button type="button" class="btn btn-secondary filter-button force-active"
                                    data-status="completed">COMPLETED</button>
                            </div>
                            <ul id="todo-list" class="todo-width">
                            </ul>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="casesTable" class="display" style="min-width: 845px; width: 100%;">
                        <thead>
                            <tr>
                                <th>CaseID</th>
                                <th>Client Name</th>
                                <th>Client Mobile</th>
                                <th>Opponent Name</th>
                                <th>Court Category</th>
                                <th>Court Address</th>
                            </tr>
                        </thead>
                    </table>
                    <div class="custom-spinner-container">
                        <div class="custom-spinner"></div>
                        <!-- <div>Loading...</div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('custom-script')
@include('common.script')
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<!-- <script src="{{asset('js/dashboard-1.js')}}"></script> -->
<script src="{{asset('js/custom_files/todo.js')}}"></script>
<script src="{{asset('js/custom_files/dashboard_data.js')}}"></script>
<script type="text/javascript">
    window.setTimeout(function() {
        $(".alert").alert('close');
    }, 10000);
</script>

@endpush
@endif
