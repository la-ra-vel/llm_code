<style>
    .metismenu li a {
        color: #ffffff !important;
    }
    i {
        color: #ffffff !important;
    }
    .mm-active > a::before {
        background-color: #FFFFFF !important;
    }
    li.mm-active {
        color: #FFFFFF !important;
    }
    a::before {
        background-color: #FFFFFF !important;
    }
    .dlabnav .metismenu .has-arrow:after {
        border-top-color: #ffffff !important;
        border-left-color: #ffffff !important;
    }
    .nested-item {
        background-color: #114944 !important;
        margin: 5px !important;
        border-radius: 5px;
    }
</style>

<div class="dlabnav" style="background-color: #114944;">
    <div class="dlabnav-scroll">
        <ul class="metismenu" id="menu">
            <!-- Clients -->
            @php $clientsAccess = checkRolePermission('clients'); @endphp
            @if($clientsAccess && $clientsAccess->access == 1)
                <li>
                    <a href="{{route('clients')}}" class="" aria-expanded="false">
                        <i class="flaticon-381-user-4"></i>
                        <span class="nav-text">Clients</span>
                    </a>
                </li>
            @endif
            
            <!-- Cases -->
            @php 
                $casesAccess = checkRolePermission('cases'); 
                
            @endphp
            @if($casesAccess && $casesAccess->access == 1)
                <li>
                    <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                        <i class="flaticon-022-copy"></i>
                        <span class="nav-text">Cases</span>
                    </a>
                    <ul aria-expanded="false">
                        
                            <li><a class="nested-item" href="{{route('case.index')}}">List</a></li>
                        
                            <li><a class="nested-item" href="{{route('create.case')}}">New Case</a></li>
                        
                    </ul>
                </li>
            @endif
            
            <!-- Invoices -->
            @php $invoicesAccess = checkRolePermission('invoice'); @endphp
            @if($invoicesAccess && $invoicesAccess->access == 1)
                <li>
                    <a href="{{route('invoices')}}" class="" aria-expanded="false">
                        <i class="flaticon-072-printer"></i>
                        <span class="nav-text">Invoice</span>
                    </a>
                </li>
            @endif
            
            <!-- Quotations -->
            @php $quotationsAccess = checkRolePermission('quotations'); @endphp
            @if($quotationsAccess && $quotationsAccess->access == 1)
                <li>
                    <a href="{{route('quotations.index')}}" class="" aria-expanded="false">
                        <i class="flaticon-043-menu"></i>
                        <span class="nav-text">Quotation</span>
                    </a>
                </li>
            @endif
            
            <!-- Court -->
            @php 
                $courtAccess = checkRolePermission('court');
                $courtListAccess = checkRolePermission('court_list');
                $courtCategoryAccess = checkRolePermission('court_category');
            @endphp
            @if($courtAccess && $courtAccess->access == 1)
                <li>
                    <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                        <i class="flaticon-013-checkmark"></i>
                        <span class="nav-text">Court</span>
                    </a>
                    <ul aria-expanded="false">
                        <li><a class="nested-item" href="{{route('courts.index')}}">Court List</a></li>
                        <li><a class="nested-item" href="{{route('court_category.index')}}">Court Categories</a></li>
                    </ul>
                </li>
            @endif
            
            <!-- Users -->
            @php 
            $userManagementAccess = checkRolePermission('user-management');
                $usersAccess = checkRolePermission('users');
                $rolesAccess = checkRolePermission('roles');
            @endphp
            @if($userManagementAccess && $userManagementAccess->access == 1)
                <li>
                    <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                        <i class="flaticon-381-user-7"></i>
                        <span class="nav-text">Users</span>
                    </a>
                    <ul aria-expanded="false">
                        @if($usersAccess && $usersAccess->access == 1)
                            <li><a class="nested-item" href="{{route('users')}}">Users</a></li>
                        @endif
                        @if($rolesAccess && $rolesAccess->access == 1)
                            <li><a class="nested-item" href="{{route('roles')}}">Roles</a></li>
                        @endif
                    </ul>
                </li>
            @endif

            <!-- Master Data -->
            @php 
                $masterDataAccess = checkRolePermission('master_data');
                
                $feeDescriptionAccess = checkRolePermission('fee_description');
                $caseActsAccess = checkRolePermission('case_acts');
            @endphp
            @if($masterDataAccess && $masterDataAccess->access == 1)
                <li>
                    <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                        <i class="flaticon-086-star"></i>
                        <span class="nav-text">Master Data</span>
                    </a>
                    <ul aria-expanded="false">
                            <li><a class="nested-item" href="{{route('countries')}}">Country</a></li>
                            <li><a class="nested-item" href="{{route('states')}}">State</a></li>
                            <li><a class="nested-item" href="{{route('cities')}}">City</a></li>
                        @if($feeDescriptionAccess && $feeDescriptionAccess->access == 1)
                            <li><a class="nested-item" href="{{route('fee.description')}}">Fee Description</a></li>
                        @endif
                        @if($caseActsAccess && $caseActsAccess->access == 1)
                            <li><a class="nested-item" href="{{route('case.acts')}}">Case Acts</a></li>
                        @endif
                    </ul>
                </li>
            @endif
            
            <!-- Settings -->
            @php 
                $settingsAccess = checkRolePermission('settings');
                $generalSettingsAccess = checkRolePermission('general_settings');
                $smtpSettingsAccess = checkRolePermission('smtp_settings');
            @endphp
            @if($settingsAccess && $settingsAccess->access == 1)
                <li>
                    <a class="has-arrow" href="javascript:void(0);" aria-expanded="false">
                        <i class="fa-solid fa-gear"></i>
                        <span class="nav-text">Settings</span>
                    </a>
                    <ul aria-expanded="false">
                        @if($generalSettingsAccess && $generalSettingsAccess->access == 1)
                            <li><a class="nested-item" href="{{route('general.settings')}}">General settings</a></li>
                        @endif
                        @if($smtpSettingsAccess && $smtpSettingsAccess->access == 1)
                            <li><a class="nested-item" href="{{route('email.config')}}">SMTP Settings</a></li>
                        @endif
                    </ul>
                </li>
            @endif
        </ul>
    </div>
</div>
