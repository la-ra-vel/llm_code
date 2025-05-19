<div class="basic-form">
    <form id="roleForm" action="{{route('role.store')}}">

        <div class="row">

            <x-custom-input name="role_name" type="text" :class="'form-control form-control-sm'" label="Role Name"
                placeholder="Enter Role name" :value="old('role_name')" :id="'role_name'" :required="true"
                :mdClass="'col-md-3'" />



            <x-custom-dropdown name="txtaccess" :options="$rolesArr" label="Permissions" :selected="$selectedRoleId"
                :required="true" :multiple="true" :keys="true" :mdClass="'col-md-6'" />

            <x-custom-button type="submit" name="Save" :class="'roleSubmitBtn'" />
            <x-custom-button type="button" name="Clear" :icon="'fas fa-times-circle'"
                                    :class="'clear-button'" :btnColor="'secondary'" />
            @foreach($rolesArr as $key => $data)
            <x-custom-input name="txtModID[{{ $key }}]" type="hidden" :class="''" label="" value="{{ $data['id'] }}"
                :id="''" :required="false" :mdClass="'col-md-1'" />

            <x-custom-input name="txtModname[{{ $key }}]" type="hidden" :class="''" label="" value="{{ $data['name'] }}"
                :id="''" :required="false" :mdClass="'col-md-1'" />

            <x-custom-input name="txtModpage[{{ $key }}]" type="hidden" :class="''" label=""
                value="{{ $data['module_page'] }}" :id="''" :required="false" :mdClass="'col-md-1'" />
            @endforeach

        </div>

    </form>
</div>
