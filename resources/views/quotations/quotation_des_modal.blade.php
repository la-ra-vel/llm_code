<style>
    .modal-body {
        max-height: 400px;
        /* Adjust the height as needed */
        overflow-y: auto;
    }
</style>
<div class="modal fade bd-example-modal-lg" id="quotationDesModal" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal">
                </button>
            </div>
            <div class="modal-body">
                <form id="QuotationDesForm" action="" method="">
                    <div class="row">

                        <input type="hidden" name="quotation_id">
                        <x-custom-input name="description" type="text" :class="'form-control form-control-sm'"
                            label="Description" placeholder="Enter Description" :value="old('description')"
                            :id="'quotationDescription'" :required="true" :mdClass="'col-md-6'" />

                        <x-custom-input name="amount" type="text" :class="'form-control form-control-sm'" label="Amount"
                            placeholder="Enter amount" :value="old('amount')" :id="'amount'" :required="true"
                            :mdClass="'col-md-3'" />

                        <x-custom-button type="submit" name="Save" :class="'QuotationDesSubmitBtn'" />

                    </div>
                </form>
                <hr>
                <div class="row">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <th width="5%">#</th>
                                <th width="40%">Description</th>
                                <th width="10%">Amount</th>
                                <th width="15%">Action Date</th>
                                <th width="30%">Action</th>
                            </thead>
                            <tbody id="quotationDesTable">

                            </tbody>
                        </table>
                    </div>

                </div>

            </div>

            <!-- <div class="modal-footer">
                <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div> -->
        </div>
    </div>
</div>
