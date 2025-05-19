<style>
    .metismenu li a{
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
    a::before{
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
<div class="dlabnav" style="background-color: #114944;>
    <div class="dlabnav-scroll">
        <!--div class="dropdown header-profile2 ">
            <a class="nav-link " href="javascript:void(0);" role="button" data-bs-toggle="dropdown">
                <div class="header-info2 d-flex align-items-center">
                    <img src="@if(Auth::guard('web')->user()->logo) {{getFile('users',Auth::guard('web')->user()->logo)}} @else {{getFile('/','no-image.png')}} @endif"
                        alt="">
                    <div class="d-flex align-items-center sidebar-info">
                        <div>
                            <span class="font-w400 d-block">Profile</span>
                            <small class="text-end font-w400">@if(Auth::guard('web')->user()->user_type=='super_admin') Superadmin @else Staff Member @endif</small>
                        </div>
                        <i class="fas fa-chevron-down"></i>
                    </div>

                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-end">
                <a href="{{route('profile',Auth::guard('web')->user()->username)}}" class="dropdown-item ai-icon ">
                    <svg xmlns="http://www.w3.org/2000/svg" class="text-primary" width="18" height="18"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <span class="ms-2">Profile </span>
                </a>
                < <a href="email-inbox.html" class="dropdown-item ai-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="text-success" width="18" height="18"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                        </path>
                        <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>
                    <span class="ms-2">Inbox </span>
                </a>>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="javascript:void(0);" onclick="event.preventDefault();
                                        this.closest('form').submit();" class="dropdown-item ai-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="text-danger" width="18" height="18"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                        <span class="ms-2">Logout </span>
                    </a>
                </form>
            </div>
        </div-->
        <ul class="metismenu" id="menu">
            <li><a href="{{route('clients')}}" class="" aria-expanded="false">
                    <i class="flaticon-381-user-4"></i>
                    <span class="nav-text">Clients</span>
                </a>
            </li>
            
            <li><a class="has-arrow" href="javascript:void()" aria-expanded="false">
                    <i class="flaticon-022-copy"></i>
                    <span class="nav-text">Cases</span>
                </a>
                <ul aria-expanded="false">
                    <li><a class="nested-item" href="{{route('case.index')}}">List</a></li>
                    <li><a class="nested-item" href="{{route('create.case')}}">New Case</a></li>
                </ul>
            </li>
            
            <li><a href="{{route('invoices')}}" class="" aria-expanded="false">
                    <i class="flaticon-072-printer"></i>
                    <span class="nav-text">Invoice</span>
                </a>
            </li>
            
            <li><a href="{{route('quotations.index')}}" class="" aria-expanded="false">
                    <i class="flaticon-043-menu"></i>
                    <span class="nav-text">Quotation</span>
                </a>
            </li>
            
            <li><a class="has-arrow " href="javascript:void()" aria-expanded="false">
                    <i class="flaticon-013-checkmark"></i>
                    <span class="nav-text">Court</span>
                </a>
                <ul aria-expanded="false">
                    <li><a class="nested-item" href="{{route('courts.index')}}">Court List</a></li>
                    <li><a class="nested-item" href="{{route('court_category.index')}}">Court Categories</a></li>
                </ul>
            </li>
            
            <li><a class="has-arrow " href="javascript:void()" aria-expanded="false">
                    <i class="flaticon-381-user-7"></i>
                    <span class="nav-text">Users</span>
                </a>
                <ul aria-expanded="false">
                    <li><a class="nested-item" href="{{route('users')}}">Users</a></li>
                    <li><a class="nested-item" href="{{route('roles')}}">Roles</a></li>

                </ul>

            </li>

            <li><a class="has-arrow " href="javascript:void()" aria-expanded="false">
                    <i class="flaticon-086-star"></i>
                    <span class="nav-text">Master Data</span>
                </a>
                <ul aria-expanded="false">
                    <li><a class="nested-item" href="{{route('countries')}}">Country</a></li>
                    <li><a class="nested-item" href="{{route('states')}}">State</a></li>
                    <li><a class="nested-item" href="{{route('cities')}}">City</a></li>
                    <li><a class="nested-item" href="{{route('fee.description')}}">Fee Description</a></li>
                    <li><a class="nested-item" href="{{route('case.acts')}}">Case Acts</a></li>

                </ul>
            </li>
            
            <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                    <i class="fa-solid fa-gear"></i>

                    <span class="nav-text">Settings</span>
                    <!-- <span class="badge badge-xs style-1 badge-danger">New</span> -->
                </a>
                <ul aria-expanded="false">
                    <li><a class="nested-item" href="{{route('general.settings')}}">General settings</a></li>
                    <li><a class="nested-item" href="{{route('email.config')}}">SMTP Settings</a></li>
                    <!-- <li><a href="email-template.html">Email Template</a></li> -->
                    <!-- <li><a href="blog.html">Blog</a></li> -->
                </ul>
            </li>
            
            <!-- <li><a class="has-arrow " href="javascript:void()" aria-expanded="false">
                    <i class="flaticon-041-graph"></i>
                    <span class="nav-text">Quotations</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="chart-flot.html">Quotations List</a></li>
                    <li><a href="chart-morris.html">New Quotation</a></li>
                </ul>
            </li> -->

            
            <!-- <li><a class="has-arrow " href="javascript:void()" aria-expanded="false">
                    <i class="flaticon-093-waving"></i>
                    <span class="nav-text">Customers</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="job-list.html">Customers Lists</a></li>

                </ul>
            </li> -->
            
            <!-- <li><a class="has-arrow " href="javascript:void()" aria-expanded="false">
                    <i class="flaticon-050-info"></i>
                    <span class="nav-text">Apps</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="app-profile.html">Profile</a></li>
                    <li><a href="edit-profile.html">Edit Profile <span
                                class="badge badge-xs badge-danger ms-3">New</span></a></li>
                    <li><a href="post-details.html">Post Details</a></li>
                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false">Email</a>
                        <ul aria-expanded="false">
                            <li><a href="email-compose.html">Compose</a></li>
                            <li><a href="email-inbox.html">Inbox</a></li>
                            <li><a href="email-read.html">Read</a></li>
                        </ul>
                    </li>
                    <li><a href="app-calender.html">Calendar</a></li>
                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false">Shop</a>
                        <ul aria-expanded="false">
                            <li><a href="ecom-product-grid.html">Product Grid</a></li>
                            <li><a href="ecom-product-list.html">Product List</a></li>
                            <li><a href="ecom-product-detail.html">Product Details</a></li>
                            <li><a href="ecom-product-order.html">Order</a></li>
                            <li><a href="ecom-checkout.html">Checkout</a></li>
                            <li><a href="ecom-invoice.html">Invoice</a></li>
                            <li><a href="ecom-customers.html">Customers</a></li>
                        </ul>
                    </li>
                </ul>
            </li> -->

            
            <!--
            <li><a class="has-arrow " href="javascript:void()" aria-expanded="false">
                    <i class="flaticon-045-heart"></i>
                    <span class="nav-text">Plugins</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="uc-select2.html">Select 2</a></li>
                    <li><a href="uc-nestable.html">Nestedable</a></li>
                    <li><a href="uc-noui-slider.html">Noui Slider</a></li>
                    <li><a href="uc-sweetalert.html">Sweet Alert</a></li>
                    <li><a href="uc-toastr.html">Toastr</a></li>
                    <li><a href="map-jqvmap.html">Jqv Map</a></li>
                    <li><a href="uc-lightgallery.html">Light Gallery</a></li>
                </ul>
            </li>
            <li><a href="widget-basic.html" class="" aria-expanded="false">
                    <i class="flaticon-013-checkmark"></i>
                    <span class="nav-text">Widget</span>
                </a>
            </li>
            <li><a class="has-arrow " href="javascript:void()" aria-expanded="false">
                    <i class="flaticon-072-printer"></i>
                    <span class="nav-text">Forms</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="form-element.html">Form Elements</a></li>
                    <li><a href="form-wizard.html">Wizard</a></li>
                    <li><a href="form-ckeditor.html">CkEditor</a></li>
                    <li><a href="form-pickers.html">Pickers</a></li>
                    <li><a href="form-validation.html">Form Validate</a></li>
                </ul>
            </li>
            <li><a class="has-arrow " href="javascript:void()" aria-expanded="false">
                    <i class="flaticon-043-menu"></i>
                    <span class="nav-text">Table</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="table-bootstrap-basic.html">Bootstrap</a></li>
                    <li><a href="table-datatable-basic.html">Datatable</a></li>
                </ul>
            </li>
            <li><a class="has-arrow " href="javascript:void()" aria-expanded="false">
                    <i class="flaticon-022-copy"></i>
                    <span class="nav-text">Pages</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="page-login.html">Login</a></li>
                    <li><a href="page-register.html">Register <span
                                class="badge badge-xs badge-danger ms-3">New</span></a></li>
                    <li><a class="has-arrow" href="javascript:void()" aria-expanded="false">Error</a>
                        <ul aria-expanded="false">
                            <li><a href="page-error-400.html">Error 400</a></li>
                            <li><a href="page-error-403.html">Error 403</a></li>
                            <li><a href="page-error-404.html">Error 404</a></li>
                            <li><a href="page-error-500.html">Error 500</a></li>
                            <li><a href="page-error-503.html">Error 503</a></li>
                        </ul>
                    </li>
                    <li><a href="page-lock-screen.html">Lock Screen</a></li>
                    <li><a href="empty-page.html">Empty Page</a></li>
                </ul>
            </li> -->
        </ul>
        <!-- <div class="plus-box">
            <p class="fs-14 font-w600 mb-2">Let Jobick Managed<br>Your Resume Easily<br></p>
            <p class="plus-box-p">Lorem ipsum dolor sit amet</p>
        </div> -->
        <!-- <div class="copyright">
            <p><strong>Jobick Job Admin</strong> © 2023 All Rights Reserved</p>
            <p class="fs-12">Made with <span class="heart"></span> by DexignLab</p>
        </div> -->
    </div>
</div>
