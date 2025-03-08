<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>CLENRO DTS</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Atkinson+Hyperlegible+Mono:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">


    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        body,
        html {
            height: 100%;
            margin: 0;
            font-family: "Atkinson Hyperlegible Mono", serif;
            font-optical-sizing: auto;
            font-weight: normal;
            font-style: normal;
        }

        .bg-image {
            background-image: url("{{ asset('images/login-bg.jpg') }}");
            /* Replace with your image URL */
            background-size: cover;
            background-position: center;
            height: 100%;
            position: relative;
        }

        .login-form {
            background: rgba(255, 255, 255, 0.95);
            /* Slightly transparent white */
            padding: 40px;
            border-radius: 15px;
            width: 500px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            /* Subtle shadow */
            position: absolute;
            top: 50%;
            right: 15%;
            /* Position on the right side */
            transform: translateY(-50%);
        }

        .login-form h2 {
            margin-bottom: 30px;
            text-align: center;
            color: #345952;
            /* Primary color for heading */
            font-weight: bold;
        }

        .login-form .form-label {
            color: #345952;
            /* Primary color for labels */
            font-weight: 500;
        }

        .login-form .form-control {
            border: 1px solid #345952;
            /* Primary color for input borders */
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 20px;
            transition: border-color 0.3s ease;
        }

        .login-form .form-control:focus {
            border-color: #345952;
            /* Primary color for focused input */
            box-shadow: 0 0 5px rgba(52, 89, 82, 0.5);
            /* Subtle glow */
        }

        .login-form .btn-primary {
            background-color: #345952;
            /* Primary color for button */
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .login-form .btn-primary:hover {
            background-color: #2a4842;
            /* Darker shade for hover */
        }

        .login-form .btn-primary:active {
            background-color: #1f3631;
            /* Even darker shade for active state */
        }

        .login-form .forgot-password {
            text-align: center;
            margin-top: 15px;
        }

        .login-form .forgot-password a {
            color: #345952;
            /* Primary color for link */
            text-decoration: none;
            font-weight: 500;
        }

        .login-form .forgot-password a:hover {
            text-decoration: underline;
        }

        /* Media Query for Mobile Screens */
        @media (max-width: 767.98px) {
            .login-form {
                width: 100%;
                /* Full width on mobile */
                height: 100%;
                /* Full height on mobile */
                border-radius: 0;
                /* Remove border radius */
                padding: 20px;
                /* Adjust padding */
                position: fixed;
                /* Fixed positioning for full-screen */
                top: 0;
                right: 0;
                transform: none;
                /* Remove transform */
            }
        }
    </style>
</head>

<body>
    <div id="app" class="bg-image">
        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>

</html>