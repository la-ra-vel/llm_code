@extends("layout.layout")

@section("style")
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.bootstrap4.min.css">
<link href="{{asset('css/select2.min.css')}}" rel="stylesheet">
<link href="{{asset('css/default_date_picker.css')}}" rel="stylesheet">
<link href="{{asset('css/default.date.css')}}" rel="stylesheet">
<link href="{{asset('css/toastr.min.css')}}" rel="stylesheet">
<link href="{{asset('css/custom/yajra_pagination.css')}}" rel="stylesheet">
@endsection
@section("content")

<div class="row page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active"><a href="javascript:void(0)">Profile</a></li>
        <!-- <li class="breadcrumb-item"><a href="javascript:void(0)">Users</a></li> -->
    </ol>
</div>

<div class="row">
    <div class="col-xl-4">
        <div class="row">
            @include('update_password_modal')
            <div class="col-xl-12">
                <div class="card overflow-hidden">
                    <div class="card-body">
                        <div class="text-center">
                            <div class="profile-photo">
                                <input type="hidden" id="userID" value="{{$user->id}}">
                                <x-profile-picture name="logo" :mdClass="'img-fluid rounded-circle'" :ajax="true"
                                    :user="$user" />
                                <!-- <img src="images/profile/profile.png" width="100" class="img-fluid rounded-circle" alt=""> -->
                            </div>
                            <h3 class="mt-4 mb-1">{{$user->full_name}}</h3>
                            <p class="text-muted">{{$user->firm_name}}</p>
                            <a class="btn btn-outline-primary btn-rounded mt-3 px-5 updatePassword"
                                data-UserID="{{$user->id}}" data-URL="{{route('change.password')}}"
                                href="javascript:void();">Change Password</a>
                        </div>
                    </div>

                    <div class="card-footer pt-0 pb-0 text-center">
                        <div class="row">
                            <div class="col-4 pt-3 pb-3 border-end">
                                <h3 class="mb-1">{{$openCases}}</h3><span>Open Cases</span>
                            </div>
                            <div class="col-4 pt-3 pb-3 border-end">
                                <h3 class="mb-1">{{$closeCases}}</h3><span>Close Cases</span>
                            </div>
                            <div class="col-4 pt-3 pb-3">
                                <h3 class="mb-1">{{$actions}}</h3><span>Actions</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-8">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="profile-tab">
                            <div class="custom-tab-1">
                                <ul class="nav nav-tabs">
                                    <li class="nav-item"><a href="#my-activity" data-bs-toggle="tab"
                                            class="nav-link active show">Activity</a>
                                    </li>

                                    <li class="nav-item"><a href="#profile-settings" data-bs-toggle="tab"
                                            class="nav-link">Setting</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div id="my-activity" class="tab-pane fade active show">

                                        <div class="card-body loadmore-content dlab-scroll height370 recent-activity-wrapper"
                                            id="RecentActivityContent">

                                        </div>

                                    </div>

                                    <div id="profile-settings" class="tab-pane fade">
                                        <div class="pt-3">
                                            <div class="settings-form">
                                                <h4 class="text-primary">Account Setting</h4>
                                                <form id="userForm" action="{{route('user.update',$user->id)}}" method="PUT">
                                                    <div class="row">
                                                        <x-custom-input name="fname" type="text"
                                                            :class="'form-control form-control-sm'" label="First Name"
                                                            placeholder="Enter your first name"
                                                            :value="$user->fname ?? old('fname')" :id="'fname'"
                                                            :required="true" :mdClass="'col-md-6'" />
                                                        <x-custom-input name="lname" type="text"
                                                            :class="'form-control form-control-sm'" label="Last Name"
                                                            placeholder="Enter your last name" :value="$user->lname ?? old('lname')"
                                                            :required="true" :mdClass="'col-md-6'" :id="'lname'" />
                                                    </div>
                                                    <div class="row">
                                                        <x-custom-input name="username" type="text"
                                                            :class="'form-control form-control-sm'"
                                                            label="Username (optional)" placeholder="Enter Username"
                                                            :value="$user->username ?? old('username')" :required="false"
                                                            :mdClass="'col-md-6'" :id="'username'" readonly="true" />

                                                        <x-custom-input name="email" type="email"
                                                            :class="'form-control form-control-sm'" label="Email"
                                                            placeholder="Enter your Email" :value="$user->email ?? old('email')"
                                                            :required="true" :mdClass="'col-md-6'" :id="'email'" />
                                                    </div>

                                                    <div class="row">
                                                        <x-custom-input name="firm_name" type="text"
                                                            :class="'form-control form-control-sm'"
                                                            label="Law Firm Name" placeholder="Enter your Law Firm Name"
                                                            :value="$user->firm_name ?? old('firm_name')" :required="true"
                                                            :mdClass="'col-md-6'" :id="'firm_name'" />

                                                        <x-custom-input name="address" type="text"
                                                            :class="'form-control form-control-sm'"
                                                            label="Law Firm Address"
                                                            placeholder="Enter your Law Firm Address"
                                                            :value="$user->address ?? old('address')" :required="true"
                                                            :mdClass="'col-md-6'" :id="'address'" />
                                                    </div>
                                                    <div class="row">
                                                        <x-custom-input name="mobile" type="text"
                                                            :class="'form-control form-control-sm'" label="Mobile"
                                                            placeholder="Enter your Mobile" :value="$user->mobile ?? old('mobile')"
                                                            :required="true" :mdClass="'col-md-6'" :id="'mobile'" />

                                                        <x-custom-dropdown name="group_id" :options="$rolesArr"
                                                            label="Role" :selected="$selectedRoleId" :required="true"
                                                            :multiple="false" :mdClass="'col-md-6'" />

                                                    </div>
                                                    <div>
                                                        <x-custom-button type="submit" name="Save"
                                                            :class="'userSubmitBtn'" :mdClass="'col-md-12'" />
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<!-- row -->

@endsection

@push('custom-script')
@include('common.script')
<script src="{{asset('js/custom_files/profile.js')}}"></script>
<script src="{{asset('js/custom_files/users.js')}}"></script>
@endpush
