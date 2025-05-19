<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Meta -->
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

    <!-- Page Title -->
    <title>Jobick : Job Admin Dashboard Bootstrap 5 Template + FrontEnd</title>

    <!-- Favicon icon -->
    <link rel="shortcut icon" type="image/png" href="images/favicon.png">

    <!-- All StyleSheet -->
    <link href="{{asset('css/bootstrap-select.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/owl.carousel.css')}}" rel="stylesheet">

    <!-- Globle CSS -->
    <link href="{{asset('css/style.css')}}" rel="stylesheet">

</head>

<body>

    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="lds-ripple">
            <div></div>
            <div></div>
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->

    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">

        <!--**********************************
            Nav header start
        ***********************************-->
        @include('layout.nav_header')
        <!--**********************************
            Nav header end
        ***********************************-->

        <!--**********************************
            Chat box start
        ***********************************-->
        @include('layout.chatbox')
        <!--**********************************
            Chat box End
        ***********************************-->

        <!--**********************************
            Header start
        ***********************************-->
        @include('layout.header')
        <!--**********************************
            Header end ti-comment-alt
        ***********************************-->

        <!--**********************************
            Sidebar start
        ***********************************-->
        @include('layout.sidebar')
        <!--**********************************
            Sidebar end
        ***********************************-->

        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <!-- row -->
            <div class="container-fluid">
                @yield("content")
            </div>
        </div>
        <!--**********************************
            Content body end
        ***********************************-->

        <!--**********************************
            Footer start
        ***********************************-->
        @include('layout.footer')
        <!--**********************************
            Footer end
        ***********************************-->

        <!--**********************************
           Support ticket button start
        ***********************************-->

        <!--**********************************
           Support ticket button end
        ***********************************-->

    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->
    <!-- Modal -->
    @include('layout.partials.job_modal')

    <!--**********************************
	Scripts
***********************************-->
    <!-- Required vendors -->
    @include('layout.partials.scripts')

    @stack("custom-script")
</body>

</html>
