<?php
$general = App\Models\GeneralSetting::first();
$theme = auth()->user()->theme_mode;
?>
<!DOCTYPE html>
<html lang="en" data-theme-version="{{ $theme ?? 'light' }}">

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
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- Page Title -->
    <title>{{@$pageTitle}}</title>
    @yield("style")

    <!-- Favicon icon -->
    <link rel="shortcut icon" type="image/png" href="@if ($general->icon) {{ getFile('logo', $general->icon) }} @else {{ getFile('logo', $general->default_image) }} @endif">

    <!-- All StyleSheet -->

    <link href="{{asset('css/bootstrap-select.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/owl.carousel.css')}}" rel="stylesheet">

    <!-- Globle CSS -->
    <link href="{{asset('css/style.css')}}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">



</head>
<body style="background-color: #faf8ec;">

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
        <!-- @include('layout.chatbox') -->
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
        <div class="content-body" id="mainBody">
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
    <script>
        var navControl = document.getElementById("navControl");
        var mainBody =  document.getElementById("mainBody");
        var collapsed = 0;
        
        navControl.onclick = function() {
            if (window.innerWidth > 768) {
                if(collapsed==0){
                    mainBody.style.marginLeft = "90px";
                    collapsed = 1;
                } else if(collapsed==1){
                    mainBody.style.marginLeft = "240px";
                    collapsed = 0;
                }
            }//else if(window.innerWidth > 576){
                //mainBody.style.marginLeft = "90px";
            //}else if(window.innerWidth <= 576){
                //mainBody.style.marginLeft = "10px";
            ////}
        }
        
        

    </script>
</body>
</html>
