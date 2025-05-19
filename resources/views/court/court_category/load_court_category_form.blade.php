<div class="basic-form">
    <form id="courtCategoryForm" action="{{route('court_category.store')}}">

        <div class="row">


            <x-custom-input name="name" type="text" :class="'form-control form-control-sm'" label="Court Category"
                placeholder="Enter Court Category" :value="old('name')" :id="'name'" :required="true"
                :mdClass="'col-md-3'" />

            <x-custom-button type="submit" name="Save" :class="'courtCategorySubmitBtn'" />
            <x-custom-button type="button" name="Clear" :icon="'fas fa-times-circle'"
                                    :class="'clear-button'" :btnColor="'secondary'" />

        </div>

    </form>
</div>
