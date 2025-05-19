<div class="basic-form">
    <form id="feeDescriptionForm" action="{{route('store.fee.description')}}">

        <div class="row">


            <x-custom-input name="name" type="text" :class="'form-control form-control-sm'" label="Fee Description"
                placeholder="Enter Fee Description" :value="old('name')" :id="'name'" :required="true"
                :mdClass="'col-md-3'" />

            <x-custom-button type="submit" name="Save" :class="'feeDescriptionSubmitBtn'" />
            <x-custom-button type="button" name="Clear" :icon="'fas fa-times-circle'"
                                    :class="'clear-button'" :btnColor="'secondary'" />

        </div>

    </form>
</div>
