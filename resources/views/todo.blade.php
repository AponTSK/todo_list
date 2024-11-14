<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('To-Do App')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background-color: #f4f7fa;
        }

        .container {
            max-width: 600px;
        }

        .modal-content {
            border-radius: 8px;
        }

        .btn-primary,
        .btn-warning,
        .btn-danger {
            font-size: 0.875rem;
        }

        .modal-header,
        .modal-body {
            padding: 1rem;
        }

        .modal-header {
            border-bottom: 1px solid #ddd;
        }

        .form-label {
            font-weight: 600;
        }

        .form-control {
            border-radius: 4px;
            font-size: 1rem;
        }

        .list-group-item {
            border-radius: 8px;
        }

        .task-actions button {
            font-size: 0.75rem;
            margin-left: 5px;
        }
    </style>
</head>

<body>

    <div class="container mt-5">
        <h2 class="text-center mb-4">@lang('To-Do List')</h2>

        <button class="btn btn-primary btn-sm mb-3" id="addTaskBtn" data-bs-toggle="modal" data-bs-target="#taskModal">
            <i class="bi bi-plus"></i> @lang('Add Task')
        </button>

        {{-- <ul class="list-group" id="todoList">
            @foreach ($todos as $todo)
                <li class="list-group-item d-flex justify-content-between align-items-center" data-id="{{ $todo->id }}">
                    <span>{{ __($todo->task) }}</span>
                    <div class="task-actions">
                        <button class="btn btn-warning btn-sm editBtn">@lang('Edit')</button>
                        <button class="btn btn-danger btn-sm deleteBtn">@lang('Delete')</button>
                    </div>
                </li>
            @endforeach
        </ul> --}}

        <ul class="list-group" id="todoList">
            @foreach ($todos as $todo)
                <li class="list-group-item d-flex justify-content-between align-items-center" data-id="{{ $todo->id }}">
                    <span>{{ __($todo->task) }}</span>
                    <div class="task-actions">
                        <button class="btn btn-warning btn-sm editBtn" data-task="{{ $todo->task }}" data-id="{{ $todo->id }}">
                            @lang('Edit')
                        </button>
                        <button class="btn btn-danger btn-sm deleteBtn" data-id="{{ $todo->id }}">
                            @lang('Delete')
                        </button>
                    </div>
                </li>
            @endforeach
        </ul>

        <div class="modal fade" id="taskModal" tabindex="-1" aria-labelledby="taskModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="taskModalLabel">@lang('Add New Task')</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="taskForm">
                            @csrf
                            <input type="hidden" id="taskId">
                            <div class="mb-3">
                                <label for="task" class="form-label">@lang('Task')</label>
                                <input type="text" class="form-control form-control-sm" id="task" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm w-100">@lang('Save Task')</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>



    @push('script')
        <script>
            $(document).ready(function() {
                var $modal = $('.modal');
                var $form = $modal.find('form');

                $('#addTaskBtn').click(function() {
                    const action = "{{ route('todo.store') }}";
                    $('#taskModalLabel').text('@lang('Add New Task')');
                    $('#taskForm')[0].reset();
                    $('#taskId').val('');
                    $modal.find('form').attr('action', action);
                });

                $('#taskForm').submit(function(e) {
                    e.preventDefault();
                    const task = $('#task').val();
                    const taskId = $('#taskId').val();
                    const method = 'POST';
                    var url = $(this).attr('action');
                    $.ajax({
                        url: url,
                        method: method,
                        data: {
                            _token: $('input[name="_token"]').val(),
                            task: task,
                        },
                        success: function(response) {
                            $('#taskModal').modal('hide');
                            location.reload();
                        },
                        error: function(error) {
                            console.log(error);
                        }
                    });
                });

                // $('body').on('click', '.editBtn', function(e) {
                //     var task = $(this).data('task');
                //     const action = "{{ route('todo.update', ':id') }}";
                //     const taskItem = $(this).closest('li');
                //     const taskId = taskItem.data('id');
                //     const taskText = taskItem.text().trim();
                //     $('#taskId').val(taskId);
                //     $('#task').val(taskText);
                //     $('#taskModalLabel').text('@lang('Edit Task')');
                //     $modal.find('form').attr('action', action.replace(":id", taskId));
                //     $('#taskId').val('');
                //     $('#taskModal').modal('show');
                // });


                $('body').on('click', '.editBtn', function() {
                    var taskId = $(this).data('id');
                    var taskText = $(this).data('task');
                    var action = "{{ route('todo.update', ':id') }}".replace(':id', taskId);
                    $('#taskForm').attr('action', action);
                    $('#taskId').val(taskId);
                    $('#task').val(taskText);
                    $('#taskModalLabel').text('@lang('Edit Task')');
                    $('#taskModal').modal('show');
                });

                $('body').on('click', '.deleteBtn', function() {
                    var taskId = $(this).data('id');
                    if (confirm('@lang('Are you sure you want to delete this task?')')) {
                        var deleteUrl = "{{ route('todo.destroy', ':id') }}".replace(':id', taskId);
                        window.location = $(this).attr("href");
                    }
                });

                function getTaskList() {
                    $.ajax({
                        type: 'get',
                        url: "{{ route('todo.index') }}",
                        success: function(response) {
                            if (response.success) {
                                $(".brand-table-body").html(response.html);
                            }
                        },
                    })
                }



            });
        </script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
    @endpush
    @stack('script')
</body>

</html>
