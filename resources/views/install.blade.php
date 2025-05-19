<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Installation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .install-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .install-header {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }
        .install-form .form-group {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="install-container">
        @include('flash_messages')
        <div class="install-header">Install Your Application</div>
        <form id="installForm" method="POST" action="{{ route('install') }}">
        @csrf
            <div class="form-group">
                <label for="app_name">Application Name</label>
                <input type="text" class="form-control" id="app_name" name="app_name" required>
            </div>
            <div class="form-group">
                <label for="db_host">Database Host</label>
                <input type="text" class="form-control" id="db_host" name="db_host" required>
            </div>
            <div class="form-group">
                <label for="db_name">Database Name</label>
                <input type="text" class="form-control" id="db_name" name="db_name" required>
            </div>
            <div class="form-group">
                <label for="db_user">Database User</label>
                <input type="text" class="form-control" id="db_user" name="db_user" required>
            </div>
            <div class="form-group">
                <label for="db_password">Database Password</label>
                <input type="password" class="form-control" id="db_password" name="db_password">
            </div>
            <div class="form-group">
                <label for="purchase_code">Purchase Code</label>
                <input type="text" class="form-control" id="purchase_code" name="purchase_code" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 mt-3">Install</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
