<form id="customCourtDataSearchForm">

    <div class="row">
        <input type="hidden" value="open" id="default_cases">
        <x-custom-dropdown name="court_category" :options="''" label="Search Court Category" :selected="''"
            :required="false" :multiple="false" :customDropdown="'true'" :mdClass="'col-md-6'"
            :id="'searchCourtCategory'" />
        <x-custom-dropdown name="court_address" :options="''" label="Search Court Address" :selected="''"
            :required="false" :multiple="false" :customDropdown="'true'" :mdClass="'col-md-6'"
            :id="'case_court_address'" />
    </div>
    <div class="row">
        <!-- <x-custom-dropdown name="caseID" :options="''" label="Search CaseID" :selected="''"
                                    :required="false" :multiple="false" :customDropdown="'true'" :mdClass="'col-md-6'"
                                    :id="'searchCaseID'" /> -->
        <x-custom-input name="custom_search" type="text" :class="'form-control form-control-sm'" label="Search"
            placeholder="search by client name / case number / case description" :value="old(key: 'search')"
            :id="'custom_search'" :required="false" :mdClass="'col-md-6'" />

        <x-custom-button type="submit" name="Search" :class="'searchDashboardDataGridBtn'" />
        <x-custom-button type="button" name="Clear" :icon="'fas fa-times-circle'" :class="'clear-button'" />
    </div>
    <!-- <button type="button" id="clearButton" class="btn btn-secondary clear-button btn-sm">
                                        <i class="fas fa-times-circle"></i> Clear
                                    </button> -->
</form>
