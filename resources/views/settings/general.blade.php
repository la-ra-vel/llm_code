@extends("layout.layout")
@section("style")
<link href="{{asset('css/jquery.dataTables.min.css')}}" rel="stylesheet">
<link href="{{asset('css/select2.min.css')}}" rel="stylesheet">
<style>
    body {
        color: #000000 !important;
    }
    .image-container {
        position: relative;
        width: 150px;
        height: 150px;
        border: 1px solid #ddd;
        margin-bottom: 10px;
    }

    .image-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .image-container input[type="file"] {
        display: none;
    }

    .image-container label {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0, 0, 0, 0.5);
        color: #fff;
        text-align: center;
        padding: 5px;
        cursor: pointer;
    }
</style>

@endsection
@section("content")

<div class="row page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active"><a href="javascript:void(0)">Settings</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0)">General</a></li>
    </ol>
</div>
<!-- row -->

<div class="row">
    <div class="col-xl-12 col-lg-12">
        @include('flash_messages')
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">General Settings Form</h4>
            </div>
            <div class="card-body">
                <div class="basic-form">
                    <form action="{{route('save.general.settings')}}" method="post" enctype="multipart/form-data">@csrf

                        <div class="row">
                            <x-custom-input name="sitename" type="text" :class="'form-control form-control-sm'"
                                label="Site Name" placeholder="Enter your site name" :value="$general->sitename ?? old('sitename')"
                                :id="'sitename'" :required="true" :mdClass="'col-md-6'" />

                            <x-custom-input name="title" type="text" :class="'form-control form-control-sm'"
                                label="Title (Law Firm Name)" placeholder="Enter your site title" :value="$general->title ?? old('title')" :id="'title'"
                                :required="true" :mdClass="'col-md-6'" />

                            <x-custom-input name="address" type="text" :class="'form-control form-control-sm'"
                                label="Short Address" placeholder="Enter your site address" :value="$general->address ?? old('address')"
                                :id="'address'" :required="true" :mdClass="'col-md-6'" />

                            <x-custom-input name="mobile" type="text" :class="'form-control form-control-sm'"
                                label="Mobile" placeholder="Enter your site mobile" :value="$general->mobile ?? old('mobile')"
                                :id="'mobile'" :required="true" :mdClass="'col-md-6'" />

                            <x-custom-input name="email" type="email" :class="'form-control form-control-sm'"
                                label="Email" placeholder="Enter your site email" :value="$general->email ?? old('email')" :id="'email'"
                                :required="true" :mdClass="'col-md-6'" />

                                <x-custom-input name="law_firm_admin" type="text" :class="'form-control form-control-sm'"
                                label="Law Firm Admin" placeholder="Enter your law firm admin name" :value="$general->law_firm_admin ?? old('law_firm_admin')" :id="'law_firm_admin'"
                                :required="true" :mdClass="'col-md-6'" />

                                <x-custom-input name="law_firm_lawyer" type="text" :class="'form-control form-control-sm'"
                                label="Law Firm Lawyer" placeholder="Enter your law firm lawyer name" :value="$general->law_firm_lawyer ?? old('law_firm_lawyer')" :id="'law_firm_admin'"
                                :required="true" :mdClass="'col-md-6'" />

                                <x-custom-input name="copy_r" type="text" :class="'form-control form-control-sm'"
                                label="Copy Right Text" placeholder="Enter your copy right text" :value="$general->copy_r ?? old('copy_r')" :id="'law_firm_admin'"
                                :required="true" :mdClass="'col-md-6'" />

                            <div class="mb-3 col-md-3">
                                <label class="form-label">Logo</label>
                                <div class="image-container">
                                    <img src="@if ($general->logo) {{ getFile('logo', $general->logo) }} @else {{ getFile('logo', $general->default_image) }} @endif"
                                        id="logoPreview" alt="Logo">
                                    <input type="file" id="logo" name="logo"
                                        onchange="previewImage(event, 'logoPreview')">
                                    <label for="logo">Logo</label>
                                </div>
                            </div>
                            <div class="mb-3 col-md-3">
                                <label class="form-label">Icon</label>
                                <div class="image-container">
                                    <img src="@if ($general->icon) {{ getFile('logo', $general->icon) }} @else {{ getFile('logo', $general->default_image) }} @endif"
                                        id="iconPreview" alt="Icon">
                                    <input type="file" id="icon" name="icon"
                                        onchange="previewImage(event, 'iconPreview')">
                                    <label for="icon">Icon</label>
                                </div>
                            </div>
                            <div class="mb-3 col-md-3">
                                <label class="form-label">Default Image</label>
                                <div class="image-container">
                                    <img src="@if ($general->default_image) {{ getFile('logo', $general->default_image) }} @else {{ getFile('logo', $general->default_image) }} @endif"
                                        id="defaultImagePreview" alt="Default Image">
                                    <input type="file" id="default_image" name="default_image"
                                        onchange="previewImage(event, 'defaultImagePreview')">
                                    <label for="default_image">Default Image</label>
                                </div>
                            </div>

                            <div class="mb-3 col-md-3">
                                <label class="form-label">Login Image</label>
                                <div class="image-container">
                                    <img src="@if ($general->login_image) {{ getFile('logo', $general->login_image) }} @else {{ getFile('logo', $general->default_image) }} @endif"
                                        id="loginImagePreview" alt="Login Image">
                                    <input type="file" id="login_image" name="login_image"
                                        onchange="previewImage(event, 'loginImagePreview')">
                                    <label for="login_image">Login Image</label>
                                </div>
                            </div>

                        </div>

                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('custom-script')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{asset('js/custom_files/users.js')}}"></script>
<script src="{{asset('js/select2.full.min.js')}}"></script>
<script src="{{asset('js/select2-init.js')}}"></script>

<script>
    function previewImage(event, previewId) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById(previewId);
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endpush
