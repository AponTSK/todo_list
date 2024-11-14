<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'To-Do App')</title>
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
        @yield('content')
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>
