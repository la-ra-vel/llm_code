@extends("layout.layout")
@section("style")
<link href="{{asset('css/jquery.dataTables.min.css')}}" rel="stylesheet">
<link href="{{asset('css/select2.min.css')}}" rel="stylesheet">


@endsection
@section("content")
<style>
    body {
        color: #000000 !important;
    }
</style>
<div class="row page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active"><a href="javascript:void(0)">Settings</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0)">SMTP</a></li>
    </ol>
</div>
<!-- row -->

<div class="row">
    <div class="col-xl-12 col-lg-12">
        @include('flash_messages')
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">SMTP Form</h4>
            </div>
            <div class="card-body">
                <div class="basic-form">
                    <form action="{{route('save.email.config')}}" method="post" enctype="multipart/form-data">

                        @csrf

                        <div class="row">
                            <div class="form-group col-md-6">

                                <label for="">Email Method</label>
                                <select name="email_method" id="" class="form-control">

                                    <option value="php" {{ $general->email_method == 'php' ? 'selected' : '' }}>PHPMail
                                    </option>
                                    <option value="smtp" {{ $general->email_method == 'smtp' ? 'selected' : '' }}>
                                        SMTP Mail</option>

                                </select>

                            </div>

                            <div class="form-group col-md-6">

                                <label for="">Email Sent From</label>

                                <input type="email" name="email_from" class="form-control form_control"
                                    value="{{$general->email_from}}">

                            </div>

                            <div class="form-group col-md-12 smtp-config">

                                @if ($general->email_method == 'smtp')

                                <div class="row mt-2">

                                    <div class="col-md-3  mt-3">

                                        <label for="">SMTP HOST</label>
                                        <input type="text" name="smtp_config[smtp_host]" class="form-control"
                                            value="{{ @$general->smtp_config->smtp_host }}">

                                    </div>

                                    <div class="col-md-3 mt-3">

                                        <label for="">SMTP Username</label>
                                        <input type="text" name="smtp_config[smtp_username]" class="form-control"
                                            value="{{ @$general->smtp_config->smtp_username }}">

                                    </div>

                                    <div class="col-md-3 mt-3">

                                        <label for="">SMTP Password</label>
                                        <input type="text" name="smtp_config[smtp_password]" class="form-control"
                                            value="{{ @$general->smtp_config->smtp_password }}">

                                    </div>
                                    <div class="col-md-3 mt-3">

                                        <label for="">SMTP port</label>
                                        <input type="text" name="smtp_config[smtp_port]" class="form-control"
                                            value="{{ @$general->smtp_config->smtp_port }}">

                                    </div>
                                </div>
                                
                                <div class="row mt-2">
                                    <div class="col-md-6 mt-3">

                                        <label for="">SMTP Encryption</label>
                                        <select name="smtp_config[smtp_encryption]" id="encryption"
                                            class="form-control">
                                            <option value="ssl"
                                                {{ @$general->smtp_config->smtp_encryption == 'ssl' ? 'selected' : '' }}>
                                                SSL</option>
                                            <option value="tls"
                                                {{ @$general->smtp_config->smtp_encryption == 'tls' ? 'selected' : '' }}>
                                                TLS</option>
                                        </select>

                                        <code class="hint"></code>

                                    </div>

                                </div>

                                @endif

                            </div>

                            <div class="form-group col-md-12 mt-2">

                                <button type="submit" class="btn btn-primary">Update Email Configuration</button>

                            </div>

                        </div>

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
        $(function() {
            'use strict'

            $('select[name=email_method]').on('change', function() {
                if ($(this).val() == 'smtp') {
                    var html = `

                     <div class="row mt-2">

                                    <div class="col-md-3">

                                        <label for="">SMTP HOST</label>
                                        <input type="text" name="smtp_config[smtp_host]"  class="form-control" value="{{ @$general->smtp_config->smtp_host }}">

                                    </div>

                                    <div class="col-md-3">

                                        <label for="">SMTP Username</label>
                                        <input type="text" name="smtp_config[smtp_username]"  class="form-control" value="{{ @$general->smtp_config->smtp_username }}">

                                    </div>

                                    <div class="col-md-3">

                                        <label for="">SMTP Password</label>
                                        <input type="text" name="smtp_config[smtp_password]"  class="form-control" value="{{ @$general->smtp_config->smtp_password }}">

                                    </div>
                                    <div class="col-md-3">

                                        <label for="">SMTP port</label>
                                        <input type="text" name="smtp_config[smtp_port]"  class="form-control" value="{{ @$general->smtp_config->smtp_port }}">

                                    </div>

                                    <div class="col-md-6 mt-3">

                                        <label for="">SMTP Encryption</label>
                                       <select name="smtp_config[smtp_encryption]" id="" class="form-control">
                                        <option value="ssl" {{ @$general->smtp_config->smtp_encription == 'ssl' ? 'selected' : '' }}>SSL</option>
                                        <option value="tls" {{ @$general->smtp_config->smtp_encription == 'tls' ? 'selected' : '' }}>TLS</option>
                                       </select>

                                    </div>

                                </div>

                `;

                    $('.smtp-config').html(html)

                } else {
                    $('.smtp-config').html('')
                }
            })

            $('#encryption').on('change',function(){
                if($(this).val() == 'ssl'){
                    $('.hint').text("For SSL please add ssl:// before host otherwise it won't work")
                }else{
                    $('.hint').text('')
                }
            })
        })
    </script>


@endpush
