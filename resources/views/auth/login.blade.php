<?php
$general = App\Models\GeneralSetting::first();

?>
<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="DexignLab">
    <meta name="robots" content="">
    <meta name="keywords"
        content="admin dashboard, admin template, analytics, bootstrap, bootstrap 5, bootstrap 5 admin template, job board admin, job portal admin, modern, responsive admin dashboard, sales dashboard, sass, ui kit, web app, frontend">
    <meta name="description"
        content="We proudly present Jobick, a Job Admin dashboard HTML Template, If you are hiring a job expert you would like to build a superb website for your Jobick, it's a best choice.">
    <meta property="og:title" content="Jobick : Job Admin Dashboard Bootstrap 5 Template + FrontEnd">
    <meta property="og:description"
        content="We proudly present Jobick, a Job Admin dashboard HTML Template, If you are hiring a job expert you would like to build a superb website for your Jobick, it's a best choice.">
    <meta property="og:image" content="https://jobick.dexignlab.com/xhtml/social-image.png">
    <meta name="format-detection" content="telephone=no">

    <!-- Mobile Specific -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- PAGE TITLE HERE -->
    <title>Admin Login</title>

    <!-- Favicon icon -->
    <link rel="shortcut icon" type="image/png" href="@if ($general->icon) {{ getFile('logo', $general->icon) }} @else {{ getFile('logo', $general->default_image) }} @endif">
    <link href="{{asset('css/bootstrap-select.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/style.css')}}" rel="stylesheet">
    <style>
    .login-left {
        background-image: url('{{ $general->login_image ? getFile("logo", $general->login_image) : getFile("logo", $general->default_image) }}');
        background-size: cover;
        background-position: center;
        height: 100vh;
    }
</style>
</head>

<body class="vh-100" style="background-color: white;">
    <div class="h-100 d-flex">
        <div class="login-left col-md-6"></div>
        <div class="authincation h-100 col-md-6 d-flex align-items-center">
            <div class="container h-100">
                <div class="row justify-content-center h-100 align-items-center">

                    <div class="col-md-10">

                        <div class="authincation-content">

                            <div class="auth-form">
                            @include('flash_messages')
                                <h4 class="text-center mb-4">Please login to your account</h4>
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="mb-1"><strong>Email</strong></label>
                                        <input type="email" class="form-control" value="{{old('email')}}" name="email">
                                    </div>
                                    <div class="mb-3">
                                        <label class="mb-1"><strong>Password</strong></label>
                                        <input type="password" class="form-control" value="" name="password">
                                    </div>
                                    <div class="row d-flex justify-content-between mt-4 mb-2">
                                        <div class="mb-3">
                                            <div class="form-check custom-checkbox ms-1">
                                                <input type="checkbox" class="form-check-input" id="basic_checkbox_1" name="remember">
                                                <label class="form-check-label" for="basic_checkbox_1">Remember me</label>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <a href="{{route('reset.password')}}">Forgot Password?</a>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                                    </div>
                                </form>
                                <!-- <div class="new-account mt-3">
                                    <p>Don't have an account? <a class="text-primary" href="page-register.html">Sign up</a></p>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="{{asset('js/global.min.js')}}"></script>
    <script src="{{asset('js/bootstrap-select.min.js')}}"></script>
    <script src="{{asset('js/custom.min.js')}}"></script>
    <script src="{{asset('js/dlabnav-init.js')}}"></script>
    <script type="text/javascript">
    window.setTimeout(function() {
        $(".alert").alert('close');
    }, 10000);
</script>
</body>

</html>
