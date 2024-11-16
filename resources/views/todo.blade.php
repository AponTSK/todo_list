@extends('layout.app')
@section('content')
    <h2 class="text-center mb-4">@lang('To-Do List')</h2>

    <button class="btn btn-primary btn-sm mb-3 add-btn">
        <i class="bi bi-plus"></i> @lang('Add Task')
    </button>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>@lang('Task')</th>
                <th>@lang('Actions')</th>
            </tr>
        </thead>
        <tbody class="task-table-body">
            @include('todo-list')
        </tbody>
    </table>

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
                            @error('task')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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

                $('.add-btn').on('click', function(e) {
                    const action = "{{ route('todo.store') }}";
                    $modal.find('.modal-title').text('@lang('Add New Task')');
                    $modal.find('form').attr('action', action);
                    $form.trigger('reset');
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
                        type: 'POST',
                        data: formData,
                        dataType: "JSON",
                        processData: false,
                        contentType: false,
                        success: function(response) {

                            if (response.success) {
                                getTaskList();
                                $modal.modal('hide');
                            } else {
                                alert(response.message);
                            }
                        }
                    });
                });

                $('body').on('click', '.editBtn', function() {
                    var taskId = $(this).data('id');
                    var taskText = $(this).data('task');
                    const action = "{{ route('todo.update', ':id') }}";
                    $modal.find('.modal-title').text("@lang('Edit Brand')");
                    $modal.find('form').attr('action', action.replace(":id", taskId));
                    $('#taskId').val(taskId);
                    $('#task').val(taskText);
                    $modal.find('[name="name"]').val(task.name);
                    $modal.find(`button[type=submit]`).text("@lang('Edit Brand')")
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
                                getTaskList();
                            },
                            error: function(xhr, status, error) {
                                alert('@lang('An error occurred while deleting the task.')');
                            }
                        });
                    }
                });

                function getTaskList() {
                    var tableBody = $('.task-table-body');
                    $.ajax({
                        url: "{{ route('todo.index') }}",
                        type: 'GET',
                        success: function(response) {
                            if (response.success) {
                                tableBody.html(response.html);
                            }
                        }
                    });
                }
            });
        </script>
    @endpush
@endsection
