<div class="basic-form">
    <form id="stateForm" action="{{route('store.state')}}">

        <div class="row">
            <x-custom-dropdown name="country_id" :options="''" label="Search Country" :selected="''" :required="true"
                :multiple="false" :customDropdown="'true'" :mdClass="'col-md-3'" :id="'searchCountry'" />

            <x-custom-input name="name" type="text" :class="'form-control form-control-sm'" label="State Name"
                placeholder="Enter state name" :value="old('name')" :id="'name'" :required="true"
                :mdClass="'col-md-3'" />

            <x-custom-button type="submit" name="Save" :class="'stateSubmitBtn'" />
            <x-custom-button type="button" name="Clear" :icon="'fas fa-times-circle'"
            :class="'clear-button'" :btnColor="'secondary'" />
        </div>

    </form>
</div>
