<style>
    body {
        color: #000000 !important;
    }
</style>
<div class="p-4 bg-white rounded-bottom shadow-sm">
    <h4>Add Case Action Details</h4>
    <form id="actionDetailsForm" action="{{route('store.court.actions.details')}}" method="post">
        <input type="hidden" name="client_case_pid" value="{{@$editCase->id}}">
        <div class="row">
            <x-custom-input name="hearing_date" type="text" :class="'datepicker-actions form-control form-control-sm'"
                label="Action Date" placeholder="Action Date" :value="old('hearing_date')" :id="'hearing_date'"
                :required="true" :readonly="true" :mdClass="'col-md-4'" />

            <!-- <x-custom-input name="note" type="text" :class="'form-control form-control-sm'" label="Note"
                placeholder="Note" :value="old('note')" :id="'note'" :required="true" :readonly="false"
                :mdClass="'col-md-3'" /> -->

                <x-custom-textarea name="note" rows="3" cols="50" :class="'form-control form-control-sm'"
                label="Note" :id="'note'" :mdClass="'col-md-8'" :required="true" :readonly="false"
                placeholder="Action Note" :value="@$editCase->note ?? old('note')" />

            <x-custom-button type="submit" name="Save" :class="'caseActionSubmitBtn'" />
        </div>
    </form>
</div>
<div class="mt-4">
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Actions List</h4>
                </div>
                <div class="card-body">
                    @php
                    $headers = ['#', 'Hearing Date', 'Note','Created By', 'Action'];
                    $columns = [
                    ['data' => 'counter', 'name' => 'counter'],
                    ['data' => 'hearing_date', 'name' => 'hearing_date'],
                    ['data' => 'note', 'name' => 'note'],
                    ['data' => 'createdBy', 'name' => 'createdBy'],
                    ['data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false]
                    ];
                    $route = empty($editCase)
                    ? route('tabs.action-details', ['id' => ':id'])
                    : route('tabs.action-details', ['id' => $editCase->id]);
                    @endphp

                    <x-custom-table :tableID="'action_detailsTable'" :headers="$headers" ajaxUrl="{{ $route }}"
                        :columns="$columns" />
                </div>
            </div>

        </div>
    </div>
    <!-- <button type="button" class="btn btn-primary next-tab rounded-0 left-arrow-button"
        data-next="#fee-details-tab">Previous</button>
    <button type="button" class="btn btn-primary next-tab rounded-0 arrow-button"
        data-next="#payment-details-tab">Next</button> -->
</div>
