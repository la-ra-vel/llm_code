<div class="p-4 bg-white rounded-bottom shadow-sm">
    <div class="row">
        <div class="col-md-4">
            <x-widget-card :mdClass="'col-xl-12 col-xxl-12 col-lg-12 col-sm-12'" :title="'Total Fees'"
                :id="'total_fees'" :icon="'la la-rupee'" :value="$total_fees" :customClass="'widget-stat card bg-danger'" />
        </div>
        <div class="col-md-4">
            <x-widget-card :mdClass="'col-xl-12 col-xxl-12 col-lg-12 col-sm-12'" :title="'Payment Received'"
                :id="'payment_received'" :icon="'la la-rupee'" :value="$payment_received"
                :customClass="'widget-stat card bg-success'" />
        </div>
        <div class="col-md-4">
            <x-widget-card :mdClass="'col-xl-12 col-xxl-12 col-lg-12 col-sm-12'" :title="'Payment Pending'"
                :id="'pending_payment'" :icon="'la la-rupee'" :value="$pending_payment"
                :customClass="'widget-stat card bg-primary'" />
        </div>

    </div>
</div>
<div class="p-4 mt-4 bg-white rounded shadow-sm">
    <h4>Add Case Payment Details</h4>

    <form id="paymentDetailsForm" action="{{route('store.court.payment.details')}}" method="post">
        <input type="hidden" name="client_case_pid" value="{{@$editCase->id}}">
        <div class="row">
            <x-custom-input name="payment_date" type="text" :class="'datepicker-default form-control form-control-sm'"
                label="Payment Date" placeholder="Payment Date" :value="old('payment_date')" :id="'payment_date'"
                :required="true" :readonly="true" :mdClass="'col-md-3'" />

            <x-custom-dropdown name="fee_description_id" :options="''" label="Payment Description" :selected="''"
                :required="true" :multiple="false" :customDropdown="'true'" :mdClass="'col-md-3'"
                :id="'fee_description_id'" />

            <x-custom-input name="amount" type="text" :class="'form-control form-control-sm'" label="Amount"
                placeholder="Amount" :value="old('amount')" :id="'amount'" :required="true" :readonly="false"
                :mdClass="'col-md-3'" />

            <x-custom-dropdown name="payment_mode" :options="[
        'online' => 'Online',
        'cash' => 'Cash'
    ]" label="Payment Mode" selected="online" :required="true" :multiple="false" :mdClass="'col-md-3'" />
        </div>
        <div class="row">
            <x-custom-input name="remarks" type="text" :class="'form-control form-control-sm'" label="Remarks"
                placeholder="Remarks" :value="old('remarks')" :id="'remarks'" :required="true" :readonly="false"
                :mdClass="'col-md-3'" />
            <x-custom-button type="submit" name="Save" :class="'casePaymentSubmitBtn'" />
        </div>

    </form>
</div>
<div class="mt-4">
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Case Payment List</h4>
                </div>
                <div class="card-body">
                    @php
                    $headers = ['#', 'Pay Date', 'Pay Amount', 'Fee Description', 'Pay Mode', 'Remarks', 'Created By',
                    'Action'];
                    $columns = [
                    ['data' => 'counter', 'name' => 'counter'],
                    ['data' => 'payment_date', 'name' => 'payment_date'],
                    ['data' => 'amount', 'name' => 'amount'],
                    ['data' => 'description', 'name' => 'description'],
                    ['data' => 'payment_mode', 'name' => 'payment_mode'],
                    ['data' => 'remarks', 'name' => 'remarks'],
                    ['data' => 'createdBy', 'name' => 'createdBy'],
                    ['data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false]
                    ];

                    $route = empty($editCase)
                    ? route('tabs.payment-details', ['id' => ':id'])
                    : route('tabs.payment-details', ['id' => $editCase->id]);
                    @endphp

                    <x-custom-table :tableID="'payment_detailsTable'" :headers="$headers" ajaxUrl="{{ $route }}"
                        :columns="$columns" />
                </div>
            </div>

        </div>
    </div>
    <!-- <button type="button" class="btn btn-primary next-tab rounded-0 left-arrow-button"
        data-next="#note-details-tab">Previous</button>
    <button type="button" class="btn btn-primary next-tab rounded-0 arrow-button"
        data-next="#documents-tab">Next</button> -->
</div>
