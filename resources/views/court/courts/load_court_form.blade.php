<div class="basic-form">
@include('court.courts.add_category_modal')
    <form id="courtForm" action="{{route('courts.store')}}">

        <div class="row">

            <x-custom-dropdown name="city_id" :options="''" label="City" :selected="''" :required="true"
                :multiple="false" :customDropdown="'true'" :mdClass="'col-md-3'" :id="'searchCity'" />

            <x-custom-dropdown name="court_categoryID" :options="''" label="Court Category" :selected="''"
                :required="true" :multiple="false" :customDropdown="'true'" :mdClass="'col-md-3'"
                :id="'searchCourtCategory'" />

            <x-custom-input name="location" type="text" :class="'form-control form-control-sm'"
                label="Location/Police Station" placeholder="Enter Location/Police Station" :value="old('location')"
                :id="'location'" :required="false" :mdClass="'col-md-3'" />

            <x-custom-input name="court_name" type="text" :class="'form-control form-control-sm'" label="Court Name"
                placeholder="Enter Court Name" :value="old('court_name')" :id="'court_name'" :required="true"
                :mdClass="'col-md-3'" />

            <x-custom-input name="court_room_no" type="text" :class="'form-control form-control-sm'"
                label="Court Room #" placeholder="Enter Court Room #" :value="old('court_room_no')"
                :id="'court_room_no'" :required="false" :mdClass="'col-md-3'" />

            <x-custom-input name="description" type="text" :class="'form-control form-control-sm'" label="Description"
                placeholder="Enter Description" :value="old('description')" :id="'description'" :required="false"
                :mdClass="'col-md-3'" />

            <x-custom-button type="submit" name="Save" :class="'courtSubmitBtn'" />
            <x-custom-button type="button" name="Clear" :icon="'fas fa-times-circle'"
                                    :class="'clear-button'" :btnColor="'secondary'" />

        </div>

    </form>
</div>
