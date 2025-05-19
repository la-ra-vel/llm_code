<div class="basic-form">
    <form id="quotationForm" action="{{route('quotations.store')}}">

        <div class="row">




                <x-custom-input name="quotation_no" type="text" :class="'form-control form-control-sm'" label="Quotation #"
                placeholder="Client Mobile" :value="$quotation_no ?? old('quotation_no')" :id="'quotation_no'" :required="true" :readonly="true"
                :mdClass="'col-md-3'" />

                <x-custom-input name="date" type="text"
                :class="'datepicker-default form-control form-control-sm'" label="Quotation Date"
                placeholder="dd/MM/YYYY" :value=" date('d F, Y') ??old('date')" :id="'date'" :required="true"
                :readonly="false" :mdClass="'col-md-3'" />

                <x-custom-input name="subject" type="text" :class="'form-control form-control-sm'" label="Subject"
                placeholder="Enter quotation subject" :value="old('subject')" :id="'subject'" :required="true"
                :mdClass="'col-md-6'" />

                <x-custom-input name="client_name" type="text" :class="'form-control form-control-sm'" label="Client Name"
                placeholder="Enter client name" :value="old('client_name')" :id="'client_name'" :required="true"
                :mdClass="'col-md-3'" />

                <x-custom-input name="client_mobile" type="text" :class="'form-control form-control-sm'" label="Client Mobile"
                placeholder="Enter client mobile" :value="old('client_mobile')" :id="'client_mobile'" :required="true"
                :mdClass="'col-md-3'" />

                <x-custom-input name="client_address" type="text" :class="'form-control form-control-sm'" label="Client Address"
                placeholder="Enter client address" :value="old('client_address')" :id="'client_address'" :required="true"
                :mdClass="'col-md-6'" />

        </div>
        <div class="row">
            <x-custom-button type="submit" name="Save" :class="'QuotationSubmitBtn'" />
            <x-custom-button type="button" name="Clear" :icon="'fas fa-times-circle'"
                                    :class="'clear-button'" :btnColor="'secondary'" />
        </div>

    </form>
</div>
