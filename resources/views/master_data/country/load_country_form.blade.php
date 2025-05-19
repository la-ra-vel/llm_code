<div class="basic-form">
    <form id="countryForm" action="{{route('store.country')}}">

        <div class="row">


            <x-custom-input name="name" type="text" :class="'form-control form-control-sm'" label="Country Name"
                placeholder="Enter country name" :value="old('name')" :id="'name'" :required="true"
                :mdClass="'col-md-3'" />

                <x-custom-input name="code" type="text" :class="'form-control form-control-sm'" label="Country Code"
                placeholder="Enter country code" :value="old('code')" :id="'code'" :required="true"
                :mdClass="'col-md-3'" />

            <x-custom-button type="submit" name="Save" :class="'countrySubmitBtn'" />
            <x-custom-button type="button" name="Clear" :icon="'fas fa-times-circle'"
                                    :class="'clear-button'" :btnColor="'secondary'" />

        </div>

    </form>
</div>
