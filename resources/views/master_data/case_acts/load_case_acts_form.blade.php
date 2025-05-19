<div class="basic-form">
    <form id="caseActForm" action="{{route('store.case.act')}}">

        <div class="row">


            <x-custom-input name="name" type="text" :class="'form-control form-control-sm'" label="Case Act"
                placeholder="Enter Case Act" :value="old('name')" :id="'name'" :required="true"
                :mdClass="'col-md-3'" />

            <x-custom-button type="submit" name="Save" :class="'caseActSubmitBtn'" />
            <x-custom-button type="button" name="Clear" :icon="'fas fa-times-circle'"
                                    :class="'clear-button'" :btnColor="'secondary'" />

        </div>

    </form>
</div>
