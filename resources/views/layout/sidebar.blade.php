<nav class="sidebar">
  <div class="sidebar-header">
    <a href="#" class="sidebar-brand">
      CHAMPOIL<span></span>
    </a>
    <div class="sidebar-toggler not-active">
      <span></span>
      <span></span>
      <span></span>
    </div>
  </div>
  <div class="sidebar-body">
    <ul class="nav">
      <li class="nav-item nav-category">Main</li>
      <li class="nav-item {{ active_class(['/dashboard']) }}">
        <a href="{{ url('/dashboard') }}" class="nav-link">
          <i class="link-icon" data-feather="pie-chart"></i>
          <span class="link-title">Dashboard</span>
        </a>
      </li>
      <li class="nav-item nav-category">Growth</li>
      <li class="nav-item {{ active_class(['growth']) }}">
        <a href="{{ url('/growth') }}" class="nav-link">
          <i class="link-icon" data-feather="users"></i>
          <span class="link-title">Team</span>
        </a>
      </li>
      <li class="nav-item {{ active_class(['sales']) }}">
        <a href="{{ url('/sales') }}" class="nav-link">
          <i class="link-icon" data-feather="trending-up"></i>
          <span class="link-title">Sales</span>
        </a>
      </li>
      <li class="nav-item {{ active_class(['contact']) }}">
        <a href="{{ url('/contact') }}" class="nav-link">
          <i class="link-icon" data-feather="book-open"></i>
          <span class="link-title">Contacts</span>
        </a>
      </li>
      <li class="nav-item {{ active_class(['']) }}">
        <a href="{{ url('#') }}" class="nav-link">
          <i class="link-icon" data-feather="target"></i>
          <span class="link-title">Target</span>
        </a>
      </li>
      <li class="nav-item nav-category">Accounting</li>
      <li class="nav-item {{ active_class(['invoice']) }}">
        <a href="{{ url('/invoice') }}" class="nav-link">
          <i class="link-icon" data-feather="file"></i>
          <span class="link-title">Invoices</span>
        </a>
      </li>
      <li class="nav-item {{ active_class(['vendor-bills']) }}">
        <a href="{{ url('/vendor-bills') }}" class="nav-link">
          <i class="link-icon" data-feather="link"></i>
          <span class="link-title">Vendor Bills</span>
        </a>
      </li>
      <li class="nav-item {{ active_class(['report']) }}">
        <a class="nav-link" data-bs-toggle="collapse" href="#reportcomponent" role="button" aria-expanded="{{ is_active_route(['coa']) }}" aria-controls="reportcomponent">
          <i class="link-icon" data-feather="file"></i>
          <span class="link-title">Report</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse {{ show_class(['report']) }}" id="reportcomponent">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ url('coa') }}" class="nav-link {{ active_class(['coa']) }}">Balance Sheet</a>
            </li>
            <li class="nav-item">
              <a href="{{ url('coa') }}" class="nav-link {{ active_class(['coa']) }}">Profit & Loss</a>
            </li>
          </ul>
        </div>
      </li>
      <li class="nav-item {{ active_class(['coa']) }}">
        <a class="nav-link" data-bs-toggle="collapse" href="#coacomponent" role="button" aria-expanded="{{ is_active_route(['coa']) }}" aria-controls="coacomponent">
          <i class="link-icon" data-feather="settings"></i>
          <span class="link-title">Settings</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse {{ show_class(['coa']) }}" id="coacomponent">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ url('coa') }}" class="nav-link {{ active_class(['coa']) }}">Chart of Accounts</a>
            </li>
            <li class="nav-item">
              <a href="{{ url('account-type') }}" class="nav-link {{ active_class(['account-type']) }}">Account Type</a>
            </li>
            <li class="nav-item">
              <a href="{{ url('tax') }}" class="nav-link {{ active_class(['tax']) }}">Tax</a>
            </li>
            <li class="nav-item">
              <a href="{{ url('bank-account') }}" class="nav-link {{ active_class(['bank-account']) }}">Bank Account</a>
            </li>
            <li class="nav-item">
              <a href="{{ url('terms-of-payment') }}" class="nav-link {{ active_class(['terms-of-payment']) }}">Payment Terms</a>
            </li>
            <li class="nav-item">
              <a href="{{ url('journal') }}" class="nav-link {{ active_class(['journal']) }}">Journal</a>
            </li>
            <li class="nav-item">
              <a href="{{ url('journal-item') }}" class="nav-link {{ active_class(['journal-item']) }}">Journal Item</a>
            </li>
            <li class="nav-item">
              <a href="{{ url('journal-entry') }}" class="nav-link {{ active_class(['journal-entry']) }}">Journal Entry</a>
            </li>
            <li class="nav-item">
              <a href="{{ url('analytics-account') }}" class="nav-link {{ active_class(['analytics-account']) }}">Analytics Account</a>
            </li>
            <li class="nav-item">
              <a href="{{ url('analytics-plans') }}" class="nav-link {{ active_class(['analytics-plans']) }}">Analytics Plans</a>
            </li>
          </ul>
        </div>
      </li>
      <li class="nav-item nav-category">Operational</li>
      <li class="nav-item {{ active_class(['']) }}">
        <a href="{{ url('/#') }}" class="nav-link">
          <i class="link-icon" data-feather="tool"></i>
          <span class="link-title">Manufacture</span>
        </a>
      </li>
      <li class="nav-item {{ active_class(['']) }}">
        <a href="{{ url('/#') }}" class="nav-link">
          <i class="link-icon" data-feather="truck"></i>
          <span class="link-title">Delivery Sistem</span>
        </a>
      </li>
      <li class="nav-item nav-category">HC & Sustain</li>
      <li class="nav-item {{ active_class(['']) }}">
        <a href="{{ url('/#') }}" class="nav-link">
          <i class="link-icon" data-feather="users"></i>
          <span class="link-title">Employee</span>
        </a>
      </li>
      <li class="nav-item {{ active_class(['']) }}">
        <a href="{{ url('/#') }}" class="nav-link">
          <i class="link-icon" data-feather="folder"></i>
          <span class="link-title">Document Controls</span>
        </a>
      </li>
      <li class="nav-item {{ active_class(['']) }}">
        <a href="{{ url('/#') }}" class="nav-link">
          <i class="link-icon" data-feather="file-text"></i>
          <span class="link-title">FPTK</span>
        </a>
      </li>
      <li class="nav-item {{ active_class(['']) }}">
        <a href="{{ url('/#') }}" class="nav-link">
          <i class="link-icon" data-feather="user-check"></i>
          <span class="link-title">HRIS</span>
        </a>
      </li>
      
      <li class="nav-item nav-category">Warehouse Management</li>
      <li class="nav-item {{ active_class(['inventory-product']) }}">
        <a href="{{ url('/inventory-product') }}" class="nav-link">
          <i class="link-icon" data-feather="package"></i>
          <span class="link-title">Inventory</span>
        </a>
      </li>
      <li class="nav-item {{ active_class(['']) }}">
        <a class="nav-link" data-bs-toggle="collapse" href="#productcategory" role="button" aria-expanded="{{ is_active_route(['product-category']) }}" aria-controls="productcategory">
          <i class="link-icon" data-feather="settings"></i>
          <span class="link-title">Settings</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse {{ show_class(['category']) }}" id="productcategory">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ url('product-category') }}" class="nav-link {{ active_class(['product-category']) }}">Product Categories</a>
            </li>
            <li class="nav-item">
              <a href="{{ url('uom') }}" class="nav-link {{ active_class(['uom']) }}">UOM</a>
            </li>
            <li class="nav-item">
              <a href="{{ url('uom-categories') }}" class="nav-link {{ active_class(['uom-categories']) }}">UOM Categories</a>
            </li>
            <li class="nav-item">
              <a href="{{ url('warehouse-location') }}" class="nav-link {{ active_class(['warehouse-location']) }}">Warehouse Location</a>
            </li>
          </ul>
        </div>
      </li>
      <li class="nav-item nav-category">Maintenance</li>
      <li class="nav-item {{ active_class(['']) }}">
        <a href="{{ url('#') }}" class="nav-link">
          <i class="link-icon" data-feather="briefcase"></i>
          <span class="link-title">Maintenance History</span>
        </a>
      </li>
      <li class="nav-item {{ active_class(['']) }}">
        <a href="{{ url('#') }}" class="nav-link">
          <i class="link-icon" data-feather="check"></i>
          <span class="link-title">Assets</span>
        </a>
      </li>
      <li class="nav-item nav-category">Purchasing</li>
      <li class="nav-item {{ active_class(['purchase']) }}">
        <a href="{{ url('/purchase') }}" class="nav-link">
          <i class="link-icon" data-feather="credit-card"></i>
          <span class="link-title">Purchase</span>
        </a>
      </li>
      <li class="nav-item nav-category">Apps Settings</li>
      <li class="nav-item">
        <a href="#" target="_blank" class="nav-link">
          <i class="link-icon" data-feather="hash"></i>
          <span class="link-title">Documentation</span>
        </a>
      </li>
      <li class="nav-item">
        <a href="#" target="_blank" class="nav-link">
          <i class="link-icon" data-feather="hash"></i>
          <span class="link-title">Apps Settings</span>
        </a>
      </li>
      <li class="nav-item">
        <a href="#" target="_blank" class="nav-link">
          <i class="link-icon" data-feather="hash"></i>
          <span class="link-title">Users</span>
        </a>
      </li>
    </ul>
  </div>
</nav>