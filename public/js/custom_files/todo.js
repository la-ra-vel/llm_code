$(document).ready(function () {
    let page = 1;
    let loading = false;
    let hasMoreItems = true; // To track if there are more items to load

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function loadTodos(status = '', append = false) {
        if (loading || !hasMoreItems) return; // Prevent loading if already loading or no more items
        loading = true;

        $.ajax({
            url: '/todos',
            method: 'GET',
            data: { status: status, page: page },
            success: function (todos) {

                if (!append) {
                    $('#todo-list').empty();
                }
                if (todos.length === 0) {
                    hasMoreItems = false; // No more items to load
                    $('#todo-list').append('<li class="no-more-items">No more Todos</li>');
                    loading = false;
                    return;
                }

                todos.forEach(function (todo) {
                    $('.filter-button[data-status="'+status+'"]').addClass('active');
                    $('#todo-list').append('<li class="todo-item" data-id="' + todo.id + '"><input type="checkbox" class="toggle-complete" ' + (todo.is_complete ? 'checked' : '') + '> <span>' + todo.description + '<br><small>' + todo.date + ' ' + todo.time + '</small></span> <button class="delete-todo">Delete</button></li>');
                });
                loading = false;
            }
        });
    }

    $(document).on('click', '#add-todo', function () {
        let description = $('#description').val();
        let is_complete = $('#is_complete').val();

        $.ajax({
            url: '/todos',
            method: 'POST',
            data: {
                description: description,
                is_complete: is_complete
            },
            success: function (todo) {

                if (todo.status === 400) {

                    $.each(todo.errors, function (key, value) {
                        toastr.warning(value);
                    });
                    return false;
                }
                toastr.success("Todo added successfully.");
                $('#todo-list').prepend('<li class="todo-item" data-id="' + todo.id + '"><input type="checkbox" class="toggle-complete" ' + (todo.is_complete == 1 ? 'checked' : '') + '> <span>' + todo.description + '<br><small>' + todo.date + ' ' + todo.time + '</small></span> <button class="delete-todo">Delete</button></li>');
                $('#description').val('');
            },
            error: function (xhr, status, error) {

                toastr.warning(xhr.responseJSON.message);
                return false;
            }
        });
    });

    $(document).on('change', '.toggle-complete', function () {
        let id = $(this).closest('li').data('id');
        let is_complete = $(this).is(':checked') ? 1 : 0;

        $.ajax({
            url: '/todos/' + id,
            method: 'PUT',
            data: {
                is_complete: is_complete
            },
            success: function () {
                toastr.success("Todo status updated.");
                $('li[data-id="' + id + '"]').remove();
            }
        });
    });

    $(document).on('click', '.delete-todo', function () {
        let id = $(this).closest('li').data('id');

        $.ajax({
            url: '/todos/' + id,
            method: 'DELETE',
            success: function () {
                toastr.success("Todo deleted successfully.");
                $('li[data-id="' + id + '"]').remove();
            }
        });
    });

    $(document).on('click', '.filter-button', function () {
        let status = $(this).data('status');
        $('.filter-button').removeClass('active');
        $(this).addClass('active');
        page = 1; // Reset page number when filter changes
        hasMoreItems = true; // Reset the flag to allow loading more items
        loadTodos(status);
    });

    $(document).on('click', '#get-list', function () {
        page = 1; // Reset page number when getting list
        $('.filter-button').removeClass('active'); // No button active
        hasMoreItems = true; // Reset the flag to allow loading more items
        loadTodos('all'); // Load all todos without filtering
    });

    $('#todo-list').on('scroll', function () {
        let scrollTop = $(this).scrollTop();
        let innerHeight = $(this).innerHeight();
        let scrollHeight = this.scrollHeight;

        if (scrollTop + innerHeight >= scrollHeight - 10) { // Adding a small offset
            page++;
            let status = $('.filter-button.active').data('status');
            loadTodos(status, true);
        }
    });

    // Initial load - load all todos
    loadTodos('incomplete');
});
