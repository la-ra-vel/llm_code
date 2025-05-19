<div class="basic-form">
    <form id="clientForm" action="{{route('store.client')}}">

        <div class="row">

            <x-custom-dropdown name="title" :options="[
                                'Mr' => 'Mr.',
                                'Mrs' => 'Mrs.',
                                'Ms' => 'Ms.'
                            ]" label="Title" selected="mr" :id="'title'" :required="true" :multiple="false" :mdClass="'col-md-3'" />

            <x-custom-input name="fname" type="text" :class="'form-control form-control-sm'" label="First Name"
                placeholder="Enter your first name" :value="old('fname')" :id="'fname'" :required="true"
                :mdClass="'col-md-3'" />

            <x-custom-input name="lname" type="text" :class="'form-control form-control-sm'" label="Last Name"
                placeholder="Enter your last name" :value="old('fname')" :required="true" :mdClass="'col-md-3'" />

            <x-custom-input name="mobile" type="text" :class="'form-control form-control-sm'" label="Mobile"
                placeholder="Enter your Mobile" :value="old('mobile')" :required="true" :mdClass="'col-md-3'" />

            <x-custom-input name="wp_no" type="text" :class="'form-control form-control-sm'" label="WhatsApp #"
                placeholder="Enter your WhatsApp Number" :value="old('wp_no')" :required="true" :mdClass="'col-md-3'" />

            <x-custom-input name="email" type="email" :class="'form-control form-control-sm'" label="Email"
                placeholder="Enter your Email" :value="old('email')" :required="false" :mdClass="'col-md-3'" />

            <x-custom-input name="address" type="text" :class="'form-control form-control-sm'" label="Address"
                placeholder="Enter your Address" :value="old('address')" :required="true" :mdClass="'col-md-3'" />

                <x-custom-input name="city" type="text" :class="'form-control form-control-sm'" label="City/Village"
                placeholder="Enter your City/Village" :value="old('city')" :required="true" :mdClass="'col-md-3'" />

            <x-custom-input name="pincode" type="text" :class="'form-control form-control-sm'" label="Pincode"
                placeholder="Enter your Pincode" :value="old('pincode')" :required="true" :mdClass="'col-md-3'" />

            <!-- <x-custom-input name="visiting_date" type="" :class="'datepicker-default form-control form-control-sm'"
                :id="'datepicker'" label="Visiting Date" placeholder="Visiting Date" :value="old('visiting_date')"
                :required="true" :mdClass="'col-md-3'" /> -->

            <x-custom-dropdown name="gender" :options="[
                                'male' => 'Male',
                                'female' => 'Female',
                                'other' => 'Other'
                            ]" label="Gender" selected="" :required="true" :multiple="false" :mdClass="'col-md-3'" />

            <x-custom-input name="occupation" type="" :class="'form-control form-control-sm'" :id="'datepicker'"
                label="Occupation" placeholder="Occupation" :value="old('occupation')" :required="false"
                :mdClass="'col-md-3'" />

        </div>
        <div class="row">
            <x-custom-button type="submit" name="Save" :class="'clientSubmitBtn'" />
            <x-custom-button type="button" name="Clear" :icon="'fas fa-times-circle'"
                                    :class="'clear-button'" :btnColor="'secondary'" />
        </div>

    </form>
</div>
