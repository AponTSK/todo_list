@foreach ($todos as $todo)
    {{-- <li class="list-group-item d-flex justify-content-between align-items-center" data-id="{{ $todo->id }}">
        <span>{{ __($todo->task) }}</span>
        <div class="task-actions">
            <button class="btn btn-warning btn-sm editBtn" data-task="{{ __($todo->task) }}" data-id="{{ $todo->id }}">
                @lang('Edit')
            </button>
            <button class="btn btn-danger btn-sm deleteBtn" data-id="{{ $todo->id }}">
                @lang('Delete')
            </button>
        </div>
    </li> --}}

    <tr data-id="{{ $todo->id }}">
        <td>{{ __($todo->task) }}</td>
        <td>
            <div class="task-actions">
                <button class="btn btn-warning btn-sm editBtn" data-task="{{ __($todo->task) }}" data-id="{{ $todo->id }}">
                    @lang('Edit')
                </button>
                <button class="btn btn-danger btn-sm deleteBtn" data-id="{{ $todo->id }}">
                    @lang('Delete')
                </button>
            </div>
        </td>
    </tr>
@endforeach
