<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <link rel="shortcut icon" href="img/favicon.ico">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Derisk - Asbestos Survey App') }}</title>

        <!-- Error CSS -->
        <link type="text/css" rel="stylesheet" href="css/login.css" media="screen" />

        <!-- Icomoon Icons -->
        <link type="text/css" rel="stylesheet" href="fonts/icomoon/icomoon.css" />

        <!-- Scripts -->
        <script>
            window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
            ]); ?>
        </script>
    </head>
    <body class="login">
        <form method="POST" action="{{ url('/login') }}">
            {{ csrf_field() }}

            <div id="login-wrapper">
                <div id="login_header">
                    <img src="/img/logo.png" class="logo" alt="Derisk Ltd" />
                </div>
                <div class="login-user">
                    <img src="/img/user-profile.png" alt="User" />
                </div>
                <h5>Sign in to access</h5>
                <div id="inputs">
                    <div class="form-block">
                        <input type="email" id="email-area" name="email" placeholder="Email" value="" />
                        <i class="icon-envelope"></i>
                    </div>
                    <div class="form-block">
                        <input type="password" id="pwd-area" name="password" placeholder="Password" value="" />
                        <i class="icon-eye2"></i>
                    </div>
                    <input type="submit" id="connect" name="connect" value="Login" />
                </div>
            </div>
        </form>
    </body>
</html>