<div class="p-4 bg-white rounded-bottom shadow-sm">
    <form id="courtDetailsForm" @if(empty(@$editCase)) action="{{route('store.court.details')}}" @else action="{{route('store.court.details',$editCase->id)}}" @endif method="post">

        <div class="row customData" @if(!empty($editCase)) data-Client="{{json_encode($editCase->client)}}" data-CourtCategory="{{json_encode($editCase->court_category)}}" data-all_court_address="{{json_encode($courtAddress)}}"  data-case_court_address="{{json_encode($editCase->case_court_address)}}" data-selectedFeeDetails="{{json_encode($selectedFeeDetails)}}" @endif>
            
            <x-custom-input name="caseID" type="text" :class="'form-control form-control-sm'" label="CaseID"
                placeholder="Client Mobile" :value="@$editCase->caseID ?? @$caseID" :id="'caseID'" :required="true" :readonly="true"
                :mdClass="'col-md-4'" />
                
            <x-custom-dropdown name="client_id" :options="''" label="Search Client" :selected="''" :required="true"
                :multiple="false" :customDropdown="'true'" :mdClass="'col-md-4'" :id="'searchClient'" />

            <x-custom-input name="client_mobile" type="text" :class="'form-control form-control-sm'"
                label="Client Mobile" placeholder="Client Mobile" :value="old('client_mobile')" :id="'client_mobile'"
                :required="false" :readonly="true" :mdClass="'col-md-4'" />

        </div>
        <div class="row">

                <x-custom-dropdown name="court_catID" :options="''" label="Court Category" :selected="''"
                :required="true" :multiple="false" :customDropdown="'true'" :mdClass="'col-md-4'"
                :id="'searchCourtCategory'" />



            <x-custom-input name="court_case_no" type="text" :class="'form-control form-control-sm'"
                label="Court Case #" placeholder="Court Case #" :value="@$editCase->court_case_no ?? old('court_case_no')" :id="'court_case_no'"
                :required="false" :readonly="false" :mdClass="'col-md-4'" />


                <x-custom-dropdown name="case_court_address" :options="''" label="Case Court Address" :selected="''"
                :required="true" :multiple="false" :customDropdown="'true'" :mdClass="'col-md-4'"
                :id="'case_court_address'" />
<!--
            <x-custom-input name="case_court_address" type="text" :class="'form-control form-control-sm'"
                label="Case Court Address" placeholder="Case Court Address" :value="old('case_court_address')"
                :id="'case_court_address'" :required="false" :readonly="false" :mdClass="'col-md-3'" /> -->

        </div>

        <div class="row">
            <x-custom-input name="case_location" type="text" :class="'form-control form-control-sm'"
                label="Case Location" placeholder="Case Location" :value="@$editCase->case_location ?? old('case_location')" :id="'case_location'"
                :required="false" :readonly="false" :mdClass="'col-md-4'" />

            <x-custom-dropdown name="case_acts" :options="$data['caseActs']" label="Case Acts"
                :selected="$caseActIds ?? $data['selectedRoleId']" :id="'case_acts'" :required="false" :multiple="true" :mdClass="'col-md-4'" />

        </div>

        <div class="row">

            <x-custom-textarea name="case_legal_matter" rows="3" cols="50" :class="'form-control form-control-sm'"
                label="Legal Matter" :id="'case_legal_matter'" :mdClass="'col-md-8'" :required="true" :readonly="false"
                placeholder="Case Legel Matter" :value="@$editCase->case_legal_matter ?? old('case_legal_matter')" />

        </div>
        <hr class="dotted">
        <div class="row">
            <x-custom-input name="opponent_name" type="text" :class="'form-control form-control-sm'"
                label="Opponet Name" placeholder="Opponet Name" :value="@$editCase->opponent_name ?? old('opponent_name')" :id="'opponent_name'"
                :required="true" :readonly="false" :mdClass="'col-md-4'" />

            <x-custom-input name="opponent_mobile" type="text" :class="'form-control form-control-sm'"
                label="Opponent Mobile" placeholder="Opponent Mobile" :value="@$editCase->opponent_mobile ?? old('opponent_mobile')"
                :id="'opponent_mobile'" :required="false" :readonly="false" :mdClass="'col-md-4'" />

            <x-custom-input name="opponent_address" type="text" :class="'form-control form-control-sm'"
                label="Opponent Address" placeholder="Opponent Address" :value="@$editCase->opponent_address ?? old('opponent_address')"
                :id="'opponent_address'" :required="false" :readonly="false" :mdClass="'col-md-4'" />

        </div>
        <div class="row">
            <x-custom-input name="responded_adv" type="text" :class="'form-control form-control-sm'"
                label="Responded Advocate Name" placeholder="Responded Advocate Name" :value="@$editCase->responded_adv ?? old('responded_adv')"
                :id="'responded_adv'" :required="false" :readonly="false" :mdClass="'col-md-4'" />

            <x-custom-input name="responded_adv_mobile" type="text" :class="'form-control form-control-sm'"
                label="Responded Advocate Phone #" placeholder="Responded Advocate Phone #"
                :value="@$editCase->responded_adv_mobile ?? old('responded_adv_mobile')" :id="'responded_adv_mobile'" :required="false" :readonly="false"
                :mdClass="'col-md-4'" />

            <x-custom-input name="fir_no" type="text" :class="'form-control form-control-sm'" label="FIR #"
                placeholder="FIR #" :value="@$editCase->fir_no ?? old('fir_no')" :id="'fir_no'" :required="false" :readonly="false"
                :mdClass="'col-md-4'" />
        </div>
        <div class="row">

            <x-custom-input name="case_start_date" type="text"
                :class="'datepicker-default form-control form-control-sm'" label="Case Start Date"
                placeholder="Case Start Date" :value="$startDate ?? old('case_start_date')" :id="'case_start_date'" :required="false"
                :readonly="false" :mdClass="'col-md-4'" />

            <x-custom-input name="case_end_date" type="text" :class="'datepicker-default form-control form-control-sm'"
                label="Case End Date (Optional)" placeholder="Case End Date (Optional)" :value="$endDate ?? old('case_end_date')"
                :id="'case_end_date'" :required="false" :readonly="false" :mdClass="'col-md-4'" />

            <x-custom-button type="submit" name="Save" :class="'caseCourtSubmitBtn'" />
        </div>
    </form>
    <!-- <button type="button" class="btn btn-primary next-tab rounded-0 arrow-button"
        data-next="#fee-details-tab">Next</button> -->
</div>
