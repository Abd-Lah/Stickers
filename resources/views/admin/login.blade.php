<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        body {
            background-color: #e9ecef; /* Light gray background */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: white;
            padding: 2.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            width: 350px;
            transition: transform 0.3s;
        }

        .login-container:hover {
            transform: translateY(-5px); /* Subtle hover effect */
        }

        .login-container h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: #343a40; /* Darker gray */
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #495057; /* Dark gray */
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ced4da; /* Light gray border */
            border-radius: 0.25rem;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .form-group input:focus {
            border-color: #007bff; /* Blue border on focus */
            outline: none; /* Remove default outline */
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5); /* Subtle shadow */
        }

        .btn {
            width: 100%;
            padding: 0.75rem;
            background-color: #007bff; /* Blue button */
            color: white;
            border: none;
            border-radius: 0.25rem;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }

        .alert {
            background-color: #f8d7da; /* Light red background */
            color: #721c24; /* Dark red text */
            padding: 1rem;
            margin-bottom: 1rem;
            border: 1px solid #f5c6cb; /* Light red border */
            border-radius: 0.25rem;
        }

        @media (max-width: 400px) {
            .login-container {
                width: 90%; /* Responsive design */
            }
        }
    </style>
</head>

<body>

<div class="login-container">
    <h2>Admin Login</h2>

    @if ($errors->any())
        <div class="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.login.submit') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required placeholder="you@example.com">
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required placeholder="********">
        </div>
        <button type="submit" class="btn">Login</button>
    </form>
</div>

</body>

</html>
