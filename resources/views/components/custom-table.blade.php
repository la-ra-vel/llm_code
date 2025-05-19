<div class="table-responsive">

    <table class="" id="{{$tableID}}" ajaxUrl="{{ $ajaxUrl }}" style="min-width: 845px; width: 100%;">
        <thead>
            <tr>
                @foreach($headers as $header)
                    <th>{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
    </table>
    <div class="custom-spinner-container">
        <div class="custom-spinner"></div>
        <!-- <div>Loading...</div> -->
    </div>
</div>


@section("style")
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
<!-- <link href="{{asset('css/jquery.dataTables.min.css')}}" rel="stylesheet"> -->
<!-- <link href="{{asset('css/custom/pagination.css')}}" rel="stylesheet"> -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.bootstrap4.min.css">
<link href="{{asset('css/select2.min.css')}}" rel="stylesheet">
<link href="{{asset('css/default_date_picker.css')}}" rel="stylesheet">
<link href="{{asset('css/default.date.css')}}" rel="stylesheet">
<link href="{{asset('css/toastr.min.css')}}" rel="stylesheet">
<link href="{{asset('css/custom/yajra_pagination.css')}}" rel="stylesheet">
@endsection

@push('custom-script')
    <!-- <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> -->
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script>
        var dataTable;
        $(document).ready(function () {
            window.dataTables = window.dataTables || {};
            var tableID = "{{ $tableID }}";
            // var dataTable = $('#' + tableID).DataTable({
                window.dataTables[tableID] = $('#' + tableID).DataTable({
                // processing: true,
                serverSide: true,
                ajax: '{{ $ajaxUrl }}',
                columns: {!! json_encode($columns) !!},
                lengthMenu: [[10, 25, 50, 100, 500, -1], [10, 25, 50, 100, 500, "All"]],
               dom: '<"row d-flex align-items-center"<"col-sm-12 col-md-4 mt-4 text-md-start d-flex align-items-center"l><"col-sm-12 col-md-8 d-flex justify-content-end align-items-center"<"mt-4 me-3"f>B>>rtip',
                // buttons: [
                //     'copy', 'csv', 'excel', 'pdf', 'print'
                // ],
                buttons: [
                    {
                        extend: 'copy',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    },
                    {
                        extend: 'csv',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    },
                    {
                        extend: 'excel',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    }
                ],
                // language: {
                //     processing: '<div class="custom-spinner-container"><div class="custom-spinner"></div></div> Loading...'
                // },
                drawCallback: function (settings) {
                    var api = this.api();
                    var pagination = $(api.table().container()).find('.dataTables_paginate');
                    var currentPage = api.page.info().page;
                    var totalPages = api.page.info().pages;

                    // Clear previous pagination
                    pagination.html('');

                    // Add previous button
                    pagination.append('<a class="paginate_button previous ' + (currentPage == 0 ? 'disabled' : '') + '"><<</a>');

                    // Add page buttons
                    for (var i = 0; i < totalPages; i++) {
                        var active = currentPage == i ? 'current' : '';
                        pagination.append('<a class="paginate_button ' + active + '">' + (i + 1) + '</a>');
                    }

                    // Add next button
                    pagination.append('<a class="paginate_button next ' + (currentPage == totalPages - 1 ? 'disabled' : '') + '">>></a>');

                    // Attach event handlers for pagination buttons
                    pagination.find('.paginate_button').not('.disabled').on('click', function () {
                        if ($(this).hasClass('previous')) {
                            api.page('previous').draw('page');
                        } else if ($(this).hasClass('next')) {
                            api.page('next').draw('page');
                        } else {
                            api.page(parseInt($(this).text()) - 1).draw('page');
                        }
                    });
                },
                initComplete: function () {
                    $('#' + tableID).on('processing.dt', function (e, settings, processing) {
                        if (processing) {
                            $('.custom-spinner-container').show();
                        } else {
                            $('.custom-spinner-container').hide();
                        }
                    });
                }
            });
            // Show custom spinner during DataTable processing
            // $('#dynamic-table').on('processing.dt', function (e, settings, processing) {
            //     if (processing) {
            //         $('.custom-spinner-container').css('display', 'flex');
            //     } else {
            //         $('.custom-spinner-container').css('display', 'none');
            //     }
            // });
        });
    </script>
@endpush
