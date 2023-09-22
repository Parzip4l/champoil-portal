<div class="horizontal-menu">
  <nav class="navbar top-navbar">
    <div class="container">
      <div class="navbar-content">
        <a href="#" class="navbar-brand">
          CHAMPOIL<span>PORTAL</span>
        </a>
        <form class="search-form">
          <div class="input-group">
            <div class="input-group-text">
              <i data-feather="search"></i>
            </div>
            <input type="text" class="form-control" id="navbarForm" placeholder="Search here...">
          </div>
        </form>
        <ul class="navbar-nav">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="languageDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img src="{{ url('assets/images/flags/us.svg') }}" class="wd-20 me-1" title="us" alt="us"> <span class="ms-1 me-1 d-none d-md-inline-block">English</span>
                </a>
                <div class="dropdown-menu" aria-labelledby="languageDropdown">
                    <a href="javascript:;" class="dropdown-item py-2"> <img src="{{ url('assets/images/flags/us.svg') }}" class="wd-20 me-1" title="us" alt="us"> <span class="ms-1"> English </span></a>
                    <a href="javascript:;" class="dropdown-item py-2"> <img src="{{ url('assets/images/flags/fr.svg') }}" class="wd-20 me-1" title="fr" alt="fr"> <span class="ms-1"> French </span></a>
                    <a href="javascript:;" class="dropdown-item py-2"> <img src="{{ url('assets/images/flags/de.svg') }}" class="wd-20 me-1" title="de" alt="de"> <span class="ms-1"> German </span></a>
                    <a href="javascript:;" class="dropdown-item py-2"> <img src="{{ url('assets/images/flags/pt.svg') }}" class="wd-20 me-1" title="pt" alt="pt"> <span class="ms-1"> Portuguese </span></a>
                    <a href="javascript:;" class="dropdown-item py-2"> <img src="{{ url('assets/images/flags/es.svg') }}" class="wd-20 me-1" title="es" alt="es"> <span class="ms-1"> Spanish </span></a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img class="wd-30 ht-30 rounded-circle" src="{{ url('https://via.placeholder.com/30x30') }}" alt="profile">
                </a>
                <div class="dropdown-menu p-0" aria-labelledby="profileDropdown">
                <div class="d-flex flex-column align-items-center border-bottom px-5 py-3">
                    <div class="mb-3">
                    <img class="wd-80 ht-80 rounded-circle" src="{{ url('https://via.placeholder.com/80x80') }}" alt="">
                    </div>
                    <div class="text-center">
                    <p class="tx-16 fw-bolder">Amiah Burton</p>
                    <p class="tx-12 text-muted">amiahburton@gmail.com</p>
                    </div>
                </div>
                <ul class="list-unstyled p-1">
                    <li class="dropdown-item py-2">
                    <a href="{{ url('/general/profile') }}" class="text-body ms-0">
                        <i class="me-2 icon-md" data-feather="user"></i>
                        <span>Profile</span>
                    </a>
                    </li>
                    <li class="dropdown-item py-2">
                    <a href="javascript:;" class="text-body ms-0">
                        <i class="me-2 icon-md" data-feather="edit"></i>
                        <span>Edit Profile</span>
                    </a>
                    </li>
                    <li class="dropdown-item py-2">
                    <a href="javascript:;" class="text-body ms-0">
                        <i class="me-2 icon-md" data-feather="repeat"></i>
                        <span>Switch User</span>
                    </a>
                    </li>
                    <li class="dropdown-item py-2">
                    <a href="javascript:;" class="text-body ms-0">
                        <i class="me-2 icon-md" data-feather="log-out"></i>
                        <span>Log Out</span>
                    </a>
                    </li>
                </ul>
                </div>
            </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="horizontal-menu-toggle">
          <i data-feather="menu"></i>					
        </button>
      </div>
    </div>
  </nav>
  <nav class="bottom-navbar">
    <div class="container">
      <ul class="nav page-navigation">
        <li class="nav-item {{ active_class(['/dashboard']) }}">
          <a href="{{ url('/dashboard') }}" class="nav-link">
            <i class="link-icon" data-feather="pie-chart"></i>
            <span class="link-title">Dashboard</span>
          </a>
        </li>
        <li class="nav-item mega-menu {{ active_class(['growth', 'contact', 'sales']) }}">
          <a href="#" class="nav-link">
            <i class="link-icon" data-feather="trending-up"></i>
            <span class="menu-title">Sales</span>
            <i class="link-arrow"></i>
          </a>
            <div class="submenu">
                <div class="col-group-wrapper row">
                    <div class="col-group col-md-3">
                        <p class="category-heading">Teams</p>
                        <div class="submenu-item">
                            <div class="row">
                                <div class="col-md-12">
                                    <ul>
                                        <li class="nav-item"><a href="{{ url('/growth') }}" class="nav-link {{ active_class(['growth']) }}">Sales Team</a></li>
                                        <li class="nav-item"><a href="{{ url('#') }}" class="nav-link {{ active_class(['#']) }}">Target</a></li>
                                        <li class="nav-item"><a href="{{ url('/contact') }}" class="nav-link {{ active_class(['contact']) }}">Contact</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-group col-md-3">
                        <p class="category-heading">Orders</p>
                        <ul class="submenu-item">
                            <li class="nav-item"><a href="{{ url('/sales') }}" class="nav-link {{ active_class(['sales']) }}">Orders</a></li>
                            <li class="nav-item"><a href="{{ url('/invoice') }}" class="nav-link {{ active_class(['invoice']) }}">To Invoice</a></li>
                        </ul>
                    </div>
                    <div class="col-group col-md-3">
                        <p class="category-heading">Product</p>
                        <ul class="submenu-item">
                            <li class="nav-item"><a href="{{ url('#') }}" class="nav-link {{ active_class(['#']) }}">Pricelist</a></li>
                            <li class="nav-item"><a href="{{ url('#') }}" class="nav-link {{ active_class(['#']) }}">Discount & Royalty</a></li>
                        </ul>
                    </div>
                    <div class="col-group col-md-3">
                        <p class="category-heading">Reporting</p>
                        <ul class="submenu-item">
                            <li class="nav-item"><a href="{{ url('#') }}" class="nav-link {{ active_class(['#']) }}">Bar Chart</a></li>
                            <li class="nav-item"><a href="{{ url('#') }}" class="nav-link {{ active_class(['#']) }}">Line Chart</a></li>
                            <li class="nav-item"><a href="{{ url('#') }}" class="nav-link {{ active_class(['#']) }}">Pie Chart</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </li>
        <li class="nav-item mega-menu {{ active_class(['ui-components/*', 'advanced-ui/*']) }}">
            <a href="#" class="nav-link">
                <i class="link-icon" data-feather="file-text"></i>
                <span class="menu-title">Accounting</span>
                <i class="link-arrow"></i>
            </a>
            <div class="submenu">
                <div class="col-group-wrapper row">
                    <div class="col-group col-md-3">
                        <p class="category-heading">Customers</p>
                        <div class="submenu-item">
                            <div class="row">
                                <div class="col-md-12">
                                    <ul>
                                        <li class="nav-item"><a href="{{ url('/invoice') }}" class="nav-link {{ active_class(['invoice']) }}">Invoice</a></li>
                                        <li class="nav-item"><a href="{{ url('/payment') }}" class="nav-link {{ active_class(['payment']) }}">Payment</a></li>
                                        <li class="nav-item"><a href="{{ url('/inventory-product') }}" class="nav-link {{ active_class(['inventory-product']) }}">Product</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-group col-md-3">
                        <p class="category-heading">Vendors</p>
                        <ul class="submenu-item">
                            <li class="nav-item"><a href="{{ url('/vendor-bills') }}" class="nav-link {{ active_class(['vendor-bills']) }}">Bills</a></li>
                            <li class="nav-item"><a href="{{ url('#') }}" class="nav-link {{ active_class(['#']) }}">Refund</a></li>
                        </ul>
                    </div>
                    <div class="col-group col-md-3">
                        <p class="category-heading">Accounting</p>
                        <ul class="submenu-item">
                            <li class="nav-item"><a href="{{ url('journal-entry') }}" class="nav-link {{ active_class(['journal-entry']) }}">Journal Entries</a></li>
                            <li class="nav-item"><a href="{{ url('journal-item') }}" class="nav-link {{ active_class(['journal-item']) }}">Journal Items</a></li>
                            <li class="nav-item"><a href="{{ url('#') }}" class="nav-link {{ active_class(['#']) }}">Balance Sheets</a></li>
                            <li class="nav-item"><a href="{{ url('#') }}" class="nav-link {{ active_class(['#']) }}">Profit & Loss</a></li>
                        </ul>
                    </div>
                    <div class="col-group col-md-3">
                        <p class="category-heading">Configuration</p>
                        <ul class="submenu-item">
                            <li class="nav-item"><a href="{{ url('/terms-of-payment') }}" class="nav-link {{ active_class(['terms-of-payment']) }}">Payment Terms</a></li>
                            <li class="nav-item"><a href="{{ url('/bank-account') }}" class="nav-link {{ active_class(['bank-account']) }}">Bank Account</a></li>
                            <li class="nav-item"><a href="{{ url('/tax') }}" class="nav-link {{ active_class(['tax']) }}">Tax</a></li>
                            <li class="nav-item"><a href="{{ url('/journal') }}" class="nav-link {{ active_class(['journal']) }}">Journal</a></li>
                            <li class="nav-item"><a href="{{ url('/coa') }}" class="nav-link {{ active_class(['coa']) }}">Charts of Account</a></li>
                            <li class="nav-item"><a href="{{ url('/account-type') }}" class="nav-link {{ active_class(['account-type']) }}">Account Type</a></li>
                            <li class="nav-item"><a href="{{ url('/analytics-account') }}" class="nav-link {{ active_class(['analytics-account']) }}">Analytics Account</a></li>
                            <li class="nav-item"><a href="{{ url('/analytics-plans') }}" class="nav-link {{ active_class(['analytics-plans']) }}">Analytics Plans</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </li>
        <li class="nav-item mega-menu {{ active_class(['forms/*']) }}">
            <a href="#" class="nav-link">
                <i class="link-icon" data-feather="activity"></i>
                <span class="menu-title">Operational</span>
                <i class="link-arrow"></i>
            </a>
            <div class="submenu">
                <div class="col-group-wrapper row">
                    <div class="col-group col-md-4">
                        <p class="category-heading">Warehouse Management</p>
                        <ul class="submenu-item">
                            <div class="row">
                                <div class="col-md-6">
                                    <li class="nav-item"><a href="{{ url('/warehouse-location') }}" class="nav-link {{ active_class(['warehouse-location']) }}">Warehouse Location</a></li>
                                    <li class="nav-item"><a href="{{ url('/inventory-product') }}" class="nav-link {{ active_class(['inventory-product']) }}">Product</a></li>
                                    <li class="nav-item"><a href="{{ url('/product-category') }}" class="nav-link {{ active_class(['product-category']) }}">Product Categories</a></li>
                                </div>
                                <div class="col-md-6">
                                    <li class="nav-item"><a href="{{ url('/uom') }}" class="nav-link {{ active_class(['uom']) }}">Uom</a></li>
                                    <li class="nav-item"><a href="{{ url('/uom-categories') }}" class="nav-link {{ active_class(['uom-categories']) }}">Uom Categories</a></li>
                                </div>
                            </div>
                        </ul>
                    </div>
                    <div class="col-group col-md-4">
                        <p class="category-heading">Manufacture</p>
                        <div class="submenu-item">
                            <div class="row">
                                <div class="col-md-12">
                                    <ul>
                                        <li class="nav-item"><a href="{{ url('#') }}" class="nav-link {{ active_class(['#']) }}">Manufacture Orders</a></li>
                                        <li class="nav-item"><a href="{{ url('#') }}" class="nav-link {{ active_class(['#']) }}">WIP</a></li>
                                        <li class="nav-item"><a href="{{ url('#') }}" class="nav-link {{ active_class(['#']) }}">FNG</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-group col-md-4">
                        <p class="category-heading">Delivery System</p>
                        <ul class="submenu-item">
                            <li class="nav-item"><a href="{{ url('#') }}" class="nav-link {{ active_class(['#']) }}">Delivery Orders</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </li>
        <li class="nav-item {{ active_class(['charts/*', 'tables/*']) }}">
            <a href="#" class="nav-link">
                <i class="link-icon" data-feather="users"></i>
                <span class="menu-title">Human Culture</span>
                <i class="link-arrow"></i>
            </a>
            <div class="submenu">
                <div class="row">
                    <div class="col-md-6">
                        <ul class="submenu-item pe-0">
                            <li class="category-heading">Employee</li>
                            <li class="nav-item"><a href="{{ url('#') }}" class="nav-link {{ active_class(['#']) }}">Employee</a></li>
                            <li class="nav-item"><a href="{{ url('#') }}" class="nav-link {{ active_class(['#']) }}">Payroll</a></li>
                            <li class="nav-item"><a href="{{ url('#') }}" class="nav-link {{ active_class(['#']) }}">Payslip</a></li>
                            <li class="nav-item"><a href="{{ url('#') }}" class="nav-link {{ active_class(['#']) }}">Attendance Record</a></li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="submenu-item ps-0">
                            <li class="category-heading">Others</li>
                            <li class="nav-item"><a href="{{ url('#') }}" class="nav-link {{ active_class(['#']) }}">Document Controls</a></li>
                            <li class="nav-item"><a href="{{ url('#') }}" class="nav-link {{ active_class(['#']) }}">FPTK</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </li>
        <li class="nav-item {{ active_class(['icons/*']) }}">
          <a href="#" class="nav-link">
            <i class="link-icon" data-feather="anchor"></i>
            <span class="menu-title">Maintenance</span>
            <i class="link-arrow"></i>
          </a>
          <div class="submenu">
            <ul class="submenu-item">
              <li class="nav-item"><a href="{{ url('#') }}" class="nav-link {{ active_class(['icons/feather-icons']) }}">Maitenance History</a></li>
              <li class="nav-item"><a href="{{ url('#') }}" class="nav-link {{ active_class(['icons/mdi-icons']) }}">Assets</a></li>
            </ul>
          </div>
        </li>
        <li class="nav-item {{ active_class(['general/*', 'error/*', 'auth/*']) }}">
            <a href="#" class="nav-link">
                <i class="link-icon" data-feather="book"></i>
                <span class="menu-title">Purchase</span>
                <i class="link-arrow"></i>
            </a>
            <div class="submenu">
                <ul class="submenu-item">
                <li class="nav-item"><a href="{{ url('/purchase') }}" class="nav-link {{ active_class(['purchase']) }}">Purchase</a></li>
                <li class="nav-item"><a href="{{ url('/inventory-product') }}" class="nav-link {{ active_class(['inventory-product']) }}">Product</a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item {{ active_class(['general/*', 'error/*', 'auth/*']) }}">
            <a href="#" class="nav-link">
                <i class="link-icon" data-feather="filter"></i>
                <span class="menu-title">RnD</span>
                <i class="link-arrow"></i>
            </a>
            <div class="submenu">
                <ul class="submenu-item">
                <li class="nav-item"><a href="{{ url('/#') }}" class="nav-link {{ active_class(['#']) }}">Quality Check</a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
          <a href="https://www.nobleui.com/laravel/documentation/docs.html" target="_blank" class="nav-link">
            <i class="link-icon" data-feather="settings"></i>
            <span class="menu-title">Apps Settings</span></a>
        </li>
      </ul>
    </div>
  </nav>
</div>