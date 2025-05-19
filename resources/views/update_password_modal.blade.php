<div class="modal fade" id="updatePasswordModal">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Send Message</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="updatePasswordForm">
                    <div class="row">
                        <input type="hidden" name="user_id">

                        <x-custom-input name="current_password" type="password" :class="'form-control form-control-sm'"
                            label="Current Password" placeholder="Enter your Current Password" :value="old('current_password')" :required="true"
                            :mdClass="'col-md-12'" :id="'current_password'" />

                        <x-custom-input name="password" type="password" :class="'form-control form-control-sm'"
                            label="Password" placeholder="Enter your Password" :value="old('password')" :required="true"
                            :mdClass="'col-md-12'" :id="'password'" />

                        <x-custom-input name="password_confirmation" type="password" :class="'form-control form-control-sm'" label="Confirm Password" placeholder="Enter your Confirm Password"
                            :value="old('password_confirmation')" :required="true" :mdClass="'col-md-12'"
                            :id="'password_confirmation'" />
                            <x-custom-button type="submit" name="Save" :mdClass="'col-md-6'" :class="'updatePasswordBtn'" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
