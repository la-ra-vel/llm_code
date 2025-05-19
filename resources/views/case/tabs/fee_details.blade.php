<div class="p-4 rounded-bottom bg-white shadow-sm">
    <h4>Add Case Fee Details</h4>
    <form id="feeDetailsForm" action="{{route('store.court.fee.details')}}" method="post">
        <input type="hidden" name="client_case_pid" value="{{@$editCase->id}}">
        <div class="row">
            <x-custom-dropdown name="fee_description_id" :options="collect($data['feeDescription'])->sortBy('name')->values()" label="Fee Description"
                :selected="$data['selectedRoleId']" :required="true" :multiple="false" :mdClass="'col-md-3'" />

            <x-custom-input name="amount" type="text" :class="'form-control form-control-sm'" label="Amount"
                placeholder="Amount" :value="old('amount')" :id="'amount'" :required="true" :readonly="false"
                :mdClass="'col-md-3'" />

            <x-custom-input name="remarks" type="text" :class="'form-control form-control-sm'" label="Remarks"
                placeholder="Remarks" :value="old('remarks')" :id="'remarks'" :required="true" :readonly="false"
                :mdClass="'col-md-3'" />

            <x-custom-button type="submit" name="Save" :class="'caseFeeSubmitBtn'" />



        </div>
    </form>
    <hr class="dotted">
</div>
<div class="mt-4">
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Fee Details List</h4>
                </div>
                <div class="card-body">
                    @php
                        $headers = ['#', 'Fee Details', 'Amount', 'Remarks', 'Created By', 'Action'];
                        $columns = [
                            ['data' => 'counter', 'name' => 'counter'],
                            ['data' => 'fee_description', 'name' => 'fee_description'],
                            ['data' => 'amount', 'name' => 'amount'],
                            ['data' => 'remarks', 'name' => 'remarks'],
                            ['data' => 'createdBy', 'name' => 'createdBy'],
                            ['data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false]
                        ];

                        $route = empty($editCase)
                            ? route('tabs.fee-details', ['id' => ':id'])
                            : route('tabs.fee-details', ['id' => $editCase->id]);
                    @endphp


                    <x-custom-table :tableID="'fee_detailsTable'" :headers="$headers" ajaxUrl="{{$route}}"
                        :columns="$columns" />
                </div>
            </div>

        </div>
    </div>

    <!-- <div class="row">
        <table class="table table-sm table-bordered">
            <thead>
                <th>#</th>
                <th>Fee Details</th>
                <th>Amount</th>
                <th>Remarks</th>
                <th>Action</th>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>this is fee details</td>
                    <td>1000</td>
                    <td>this is Remarks</td>
                    <td><a href="javascript:void(0);">Edit</a></td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>this is fee details</td>
                    <td>10000</td>
                    <td>this is Remarks</td>
                    <td><a href="javascript:void(0);" data-ID="2">Edit</a></td>
                </tr>
            </tbody>
        </table>
    </div> -->

    <!-- <button type="button" class="btn btn-primary next-tab rounded-0 left-arrow-button"
        data-next="#court-details-tab">Previous</button>
    <button type="button" class="btn btn-primary next-tab rounded-0 arrow-button"
        data-next="#note-details-tab">Next</button> -->
</div>
