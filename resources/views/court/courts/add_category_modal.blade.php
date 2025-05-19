<div class="modal fade bd-example-modal-sm" id="courtCategoryModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Court Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal">
                </button>
            </div>
            <form id="courtCategoryForm" action="{{route('court_category.store')}}">
                <div class="modal-body">
                    <div class="row">
                        <x-custom-input name="name" type="text" :class="'form-control form-control-sm'"
                            label="Court Category" placeholder="Enter Court Category" :value="old('name')" :id="'name'"
                            :required="true" :mdClass="'col-md-6'" />
                            <x-custom-button type="submit" name="Save" :mdClass="'col-md-6'" :class="'courtCategorySubmitBtn'" />
                    </div>
                </div>

                <!-- <div class="modal-footer">

                </div> -->
            </form>
        </div>
    </div>
</div>
