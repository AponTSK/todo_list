@foreach ($todos as $todo)
    <li class="list-group-item d-flex justify-content-between align-items-center mb-2" data-id="{{ $todo->id }}">
        <span>{{ __($todo->task) }}</span>
        <div class="task-actions">
            <button class="btn btn-warning btn-sm edit-btn" data-task="{{ $todo }}">
                @lang('Edit')
            </button>
            <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $todo->id }}">
                @lang('Delete')
            </button>
        </div>
    </li>
@endforeach
