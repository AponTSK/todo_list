@extends('layout.app')
@section('content')
    <h2 class="text-center mb-4">@lang('To-Do List')</h2>

    <button class="btn btn-primary btn-sm mb-3 add-btn">
        <i class="bi bi-plus"></i> @lang('Add Task')
    </button>

    <ul class="list-group" class="todoList">
        @foreach ($todos as $todo)
            <li class="list-group-item d-flex justify-content-between align-items-center" data-id="{{ $todo->id }}">
                <span>{{ __($todo->task) }}</span>
                <div class="task-actions">
                    <button class="btn btn-warning btn-sm editBtn" data-task="{{ __($todo->task) }}" data-id="{{ $todo->id }}">
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
                    <h5 class="modal-title">@lang('Add New Task')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST">
                        @csrf
                        <input type="hidden" id="taskId">
                        <div class="mb-3">
                            <label for="task" class="form-label">@lang('Task')</label>
                            <input type="text" class="form-control form-control-sm" id="task" name="task" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm w-100">@lang('Save Task')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                var $modal = $('.modal');
                var $form = $modal.find('form');


                $('.add-btn').on('click', function() {
                    const action = "{{ route('todo.store') }}";
                    $modal.find('.modal-title').text('@lang('Add New Task')');
                    $form.trigger('reset');
                    $modal.find('form').attr('action', action);
                    $modal.modal('show');
                });

                $form.submit(function(e) {
                    e.preventDefault();

                    const formData = new FormData($(this)[0])
                    const task = $('#task').val();
                    const taskId = $('#taskId').val();

                    var url = $(this).attr('action');
                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: formData,
                        dataType: "JSON",
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            getTaskList();
                            $modal.modal('hide');
                        },
                        error: function(error) {
                            console.log(error);
                        }
                    });
                });

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

                $('body').on('click', '.deleteBtn', function(e) {
                    e.preventDefault();

                    var taskId = $(this).data('id');
                    if (confirm('@lang('Are you sure you want to delete this task?')')) {
                        var deleteUrl = "{{ route('todo.destroy', ':id') }}".replace(':id', taskId);

                        $.ajax({
                            url: deleteUrl,
                            type: 'GET',
                            success: function(response) {
                                var message = response.message || ('@lang('Task deleted successfully!')');
                                alert(message);
                                $('li[data-id="' + taskId + '"]').remove();
                            },
                            error: function(xhr, status, error) {
                                alert('@lang('An error occurred while deleting the task.')');
                            }
                        });
                    }
                });

                function getTaskList() {
                    $.ajax({
                        url: "{{ route('todo.index') }}",
                        method: 'GET',
                        dataType: 'html',
                        success: function(response) {
                            $('.todoList').html(response);
                        },
                        error: function(xhr, status, error) {
                            console.error("Error fetching task list:", status, error);
                            alert('Failed to update the task list.');
                        }
                    });
                }
            });
        </script>
    @endpush
@endsection
