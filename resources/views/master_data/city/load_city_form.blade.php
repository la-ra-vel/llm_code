<div class="basic-form">
    <form id="cityForm" action="{{route('store.city')}}">

        <div class="row">
                <x-custom-dropdown name="state_id" :options="''" label="Search State" :selected="''"
                :required="true" :multiple="false" :customDropdown="'true'" :mdClass="'col-md-3'" :id="'searchState'" />

                <x-custom-input name="name" type="text" :class="'form-control form-control-sm'" label="City Name"
                placeholder="Enter city name" :value="old('name')" :id="'name'" :required="true"
                :mdClass="'col-md-3'" />

            <x-custom-button type="submit" name="Save" :class="'citySubmitBtn'" />
            <x-custom-button type="button" name="Clear" :icon="'fas fa-times-circle'"
                                    :class="'clear-button'" :btnColor="'secondary'" />

        </div>

    </form>
</div>
