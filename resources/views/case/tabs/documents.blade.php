<div class="p-4 bg-white rounded-bottom shadow-sm">
    <h4>Add Client Documents</h4>
    <form id="documentsDetailsForm" action="{{route('store.court.document.details')}}" method="post"
        enctype="multipart/form-data">
        <input type="hidden" name="client_case_pid" value="{{@$editCase->id}}">
        <div class="row">

            <x-custom-input name="document_name" type="text" :class="'form-control form-control-sm'"
                label="Document Name" placeholder="Document Name" :value="old('document_name')" :id="'document_name'"
                :required="true" :readonly="false" :mdClass="'col-md-3'" />

            <x-custom-input name="file" type="file" :class="'form-control form-control-sm'" label="Browse File"
                placeholder="Browse File" :value="old('file')" :id="'file'" :required="false" :readonly="false"
                :mdClass="'col-md-3'" />

            <x-custom-button type="submit" name="Save" :class="'caseDocumentSubmitBtn'" />
        </div>
    </form>
</div>
<div class="mt-4">
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Document List</h4>
                </div>
                <div class="card-body">
                    @php
                    $headers = ['#', 'Document Name', 'File Name','Created By', 'Action'];
                    $columns = [
                    ['data' => 'counter', 'name' => 'counter'],
                    ['data' => 'document_name', 'name' => 'document_name'],
                    ['data' => 'file', 'name' => 'file'],
                    ['data' => 'createdBy', 'name' => 'createdBy'],
                    ['data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false]
                    ];

                    $route = empty($editCase)
                    ? route('tabs.document-details', ['id' => ':id'])
                    : route('tabs.document-details', ['id' => $editCase->id]);
                    @endphp

                    <x-custom-table :tableID="'document_detailsTable'" :headers="$headers" ajaxUrl="{{ $route }}"
                        :columns="$columns" />
                </div>
            </div>

        </div>
    </div>

    <!-- <button type="button" class="btn btn-primary next-tab rounded-0 left-arrow-button"
        data-next="#payment-details-tab">Previous</button> -->
    <!-- <button type="submit" class="btn btn-success rounded-0">Submit</button> -->
</div>
