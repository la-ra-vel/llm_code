<div class="basic-form">
    <form id="userForm" action="{{route('user.store')}}" enctype="multipart/form-data" method="POST">

        <div class="row">

            <x-custom-input name="fname" type="text" :class="'form-control form-control-sm'" label="First Name"
                placeholder="Enter your first name" :value="old('fname')" :id="'fname'" :required="true"
                :mdClass="'col-md-3'" />

            <x-custom-input name="lname" type="text" :class="'form-control form-control-sm'" label="Last Name"
                placeholder="Enter your last name" :value="old('lname')" :required="true" :mdClass="'col-md-3'"
                :id="'lname'" />

            <x-custom-input name="username" type="text" :class="'form-control form-control-sm'"
                label="Username (optional)" placeholder="Enter Username" :value="old('username')" :required="false"
                :mdClass="'col-md-3'" :id="'username'" />

            <x-custom-input name="mobile" type="text" :class="'form-control form-control-sm'" label="Mobile"
                placeholder="Enter your Mobile" :value="old('mobile')" :required="true" :mdClass="'col-md-3'"
                :id="'mobile'" />

            <x-custom-input name="email" type="email" :class="'form-control form-control-sm'" label="Email"
                placeholder="Enter your Email" :value="old('email')" :required="true" :mdClass="'col-md-3'"
                :id="'email'" />

            <x-custom-input name="firm_name" type="text" :class="'form-control form-control-sm'" label="Law Firm Name"
                placeholder="Enter your Law Firm Name" :value="old('firm_name')" :required="true" :mdClass="'col-md-3'"
                :id="'firm_name'" />

            <x-custom-input name="address" type="text" :class="'form-control form-control-sm'" label="Law Firm Address"
                placeholder="Enter your Law Firm Address" :value="old('address')" :required="true" :mdClass="'col-md-3'"
                :id="'address'" />
            <x-custom-input name="password" type="password" :class="'form-control form-control-sm'" label="Password"
                placeholder="Enter your Password" :value="old('password')" :required="true" :mdClass="'col-md-3'"
                :id="'password'" />

            <x-custom-input name="password_confirmation" type="password" :class="'form-control form-control-sm'"
                label="Confirm Password" placeholder="Enter your Confirm Password" :value="old('password_confirmation')"
                :required="true" :mdClass="'col-md-3'" :id="'password_confirmation'" />

            <x-custom-dropdown name="group_id" :options="$rolesArr" label="Role" :selected="$selectedRoleId"
                :required="true" :multiple="false" :mdClass="'col-md-3'" />

            <x-custom-button type="submit" name="Save" :class="'userSubmitBtn'" />
        </div>
        <div class="row">
            <x-profile-picture name="logo" :mdClass="'col-md-3'" />
        </div>

    </form>
</div>
