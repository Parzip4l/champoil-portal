<div class="horizontal-menu">
  <nav class="navbar top-navbar">
    <div class="container">
      <div class="navbar-content">
        <a href="#" class="navbar-brand">
          <img src="{{ url('assets/images/logo/logodesktop.png') }}" alt="">
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
                    <a href="javascript:;" class="dropdown-item py-2"> <img src="{{ url('assets/images/flags/idn.svg') }}" class="wd-20 me-1" title="idn" alt="idn"> <span class="ms-1"> Indonesia </span></a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                @php
                    // Ambil nama produk berdasarkan product_id
                    $employeeDetails = \App\Employee::where('nik', Auth::user()->employee_code)->first();
                    $employee = \App\Employee::where('nik', Auth::user()->name)->first();
                    $user = Auth::user();
                @endphp
                <img class="wd-30 ht-30 rounded-circle" src="{{ asset('images/' . $employeeDetails->gambar) }}" alt="{{$employee->nama}}">
                </a>
                <div class="dropdown-menu p-0" aria-labelledby="profileDropdown">
                <div class="d-flex flex-column align-items-center border-bottom px-5 py-3">
                    <div class="mb-3">
                    <img class="wd-80 ht-80 rounded-circle" src="{{ asset('images/' . $employeeDetails->gambar) }}" alt="{{$employee->nama}}">
                    </div>
                    <div class="text-center">
                    <p class="tx-16 fw-bolder">{{ $employee->nama }}</p>
                    <p class="tx-12 text-muted">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                <ul class="list-unstyled p-1">
                    <li class="dropdown-item py-2">
                        <a href="{{ route('MyProfile', ['nik' => Auth::user()->name])}}" class="text-body ms-0">
                            <i class="me-2 icon-md" data-feather="user"></i>
                            <span>Profile</span>
                        </a>
                    </li>
                    <li class="dropdown-item py-2">
                        <form action="{{ route('logout') }}" method="POST" id="logout_admin">
                            @csrf
                            <a href="#" class="text-body ms-0" onClick="submitForm()">
                                <i class="me-2 icon-md" data-feather="log-out"></i>
                                <span>Log Out</span>
                            </a>
                        </form>
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
  @php 
    $dataLogin = json_decode(Auth::user()->permission);
    $employee = \App\Employee::where('nik', Auth::user()->name)->first();
  @endphp 
  <nav class="bottom-navbar">
    <div class="container">
      <ul class="nav page-navigation">
        @if(in_array('superadmin_access', $dataLogin) || in_array('accounting_access', $dataLogin) || in_array('sales_access', $dataLogin))
        <li class="nav-item {{ active_class(['/dashboard']) }}">
          <a href="{{ url('/dashboard') }}" class="nav-link">
            <i class="link-icon" data-feather="pie-chart"></i>
            <span class="link-title">Dashboard</span>
          </a>
        </li>
        @endif
        @if(in_array('superadmin_access', $dataLogin)|| in_array('client_access', $dataLogin))
            <li class="nav-item">
                <a href="{{ url('#') }}" class="nav-link">
                    <i class="link-icon" data-feather="user"></i>
                        <span class="menu-title">Employee</span>
                    <i class="link-arrow"></i>
                </a>
                <div class="submenu">
                    <ul class="submenu-item">
                        <li class="nav-item"><a href="{{ url('employee') }}" class="nav-link {{ active_class(['absen']) }}">Employee Directory</a></li>
                        <li class="nav-item"><a href="{{ url('employee-resign') }}" class="nav-link {{ active_class(['kas/schedule']) }}">Employee Resign</a></li>
                    </ul>
                </div>
            </li>
        @endif
        @if(in_array('superadmin_access', $dataLogin) || in_array('am_access', $dataLogin) || in_array('dashboard_access', $dataLogin) || in_array('client_access', $dataLogin))
            <li class="nav-item">
                <a href="{{ url('#') }}" class="nav-link">
                    <i class="link-icon" data-feather="clock"></i>
                        <span class="menu-title">Time Management</span>
                    <i class="link-arrow"></i>
                </a>
                <div class="submenu">
                    <ul class="submenu-item">
                        <li class="nav-item"><a href="{{ url('absen') }}" class="nav-link {{ active_class(['absen']) }}">Attendance Record</a></li>
                        @if($employee && $employee->unit_bisnis == 'Kas' && $user->project_id == NULL)
                        <li class="nav-item"><a href="{{ route('backup.log') }}" class="nav-link {{ active_class(['ckup-log']) }}">Backup Record</a></li>
                        @endif
                        @if($employee && $employee->unit_bisnis == 'Kas')
                        <li class="nav-item"><a href="{{ route('schedule.index') }}" class="nav-link {{ active_class(['kas/schedule']) }}">Schedule</a></li>
                        @if($user->project_id == NULL)
                        <li class="nav-item"><a href="{{ url('kas/backup-schedule') }}" class="nav-link {{ active_class(['kas/backup-schedule']) }}">Backup Schedule</a></li>
                        @endif
                        @endif
                        @if($user->project_id == NULL)
                        <li class="nav-item"><a href="{{ route('shift.index') }}" class="nav-link {{ active_class(['kas/shift']) }}">Shift</a></li>
                        @endif
                        @if($employee && $employee->unit_bisnis == 'Kas'  && $user->project_id == NULL)
                        <li class="nav-item"><a href="{{ url(route('report.index') . '?periode='.date('M-Y')) }}" class="nav-link {{ active_class(['kas/report']) }}">Report</a></li>
                        @endif
                    </ul>
                </div>
            </li>
        @endif

        @if(in_array('superadmin_access', $dataLogin))
            <li class="nav-item">
                <a href="{{ url('#') }}" class="nav-link">
                    <i class="link-icon" data-feather="dollar-sign"></i>
                        <span class="menu-title">Payroll</span>
                    <i class="link-arrow"></i>
                </a>
                <div class="submenu">
                    <ul class="submenu-item">
                        <li class="nav-item"><a href="{{ url('payroll') }}" class="nav-link {{ active_class(['payroll']) }}">Payroll</a></li>
                        <li class="nav-item"><a href="{{ url('payslip') }}" class="nav-link {{ active_class(['payslip']) }}">Payrol History</a></li>
                        <li class="nav-item"><a href="{{ url('thr') }}" class="nav-link {{ active_class(['thr']) }}">THR</a></li>
                        @if($employee && $employee->unit_bisnis == 'CHAMPOIL')
                            <li class="nav-item"><a href="{{ route('payroll.ns') }}" class="nav-link {{ active_class(['payroll.ns']) }}">Payroll Frontline Officer</a></li>
                        @endif

                        @if($employee && $employee->unit_bisnis == 'Kas')
                            <li class="nav-item"><a href="{{ route('payroll-kas.index') }}" class="nav-link {{ active_class(['kas/payroll-kas']) }}">Payroll Anggota</a></li>
                        @endif

                        <li class="nav-item"><a href="{{ url('payrol-component') }}" class="nav-link {{ active_class(['payrol-component']) }}">Assign Component</a></li>
                        <li class="nav-item"><a href="{{ url('component-data') }}" class="nav-link {{ active_class(['component-data']) }}">Component Master</a></li>
                    </ul>
                </div>
            </li>
        @endif
        @if(in_array('superadmin_access', $dataLogin))
            <li class="nav-item">
                <a href="{{ route('koperasi.index') }}" class="nav-link {{ active_class(['koperasi']) }}">
                    <i class="link-icon" data-feather="credit-card"></i>
                    <span class="menu-title">Koperasi</span>
                </a>
            </li>
        @endif
        @if(in_array('pic_access', $dataLogin) || in_array('superadmin_access', $dataLogin) || in_array('am_access', $dataLogin)|| in_array('client_access', $dataLogin))
            <li class="nav-item">
                <a href="{{ route('pengajuan-schedule.index') }}" class="nav-link {{ active_class(['kas/pengajuan-schedule']) }}">
                    <i class="link-icon" data-feather="calendar"></i>
                    <span class="menu-title">Pengajuan Schedule</span>
                </a>
            </li>
        @endif
        @if(in_array('superadmin_access', $dataLogin) || in_array('am_access', $dataLogin)|| in_array('client_access', $dataLogin))
            <li class="nav-item">
                <a href="{{ route('knowledge_base.index') }}" class="nav-link">
                    <i class="link-icon" data-feather="book-open"></i>
                    <span class="menu-title">LMS</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('#') }}" class="nav-link">
                    <i class="link-icon" data-feather="clock"></i>
                    <span class="menu-title">Task Management</span>
                    <i class="link-arrow"></i>
                </a>
                <div class="submenu">
                    <ul class="submenu-item">
                        <li class="nav-item"><a href="{{ url('task') }}" class="nav-link {{ active_class(['task']) }}">Patroli</a></li>
                        <li class="nav-item"><a href="#" class="nav-link {{ active_class(['kas/schedule']) }}">Audit</a></li>
                        <li class="nav-item"><a href="{{ route('taskg.index') }}" class="nav-link {{ active_class(['tasg']) }}">Task</a></li>
                    </ul>
                </div>
            </li>
        @endif
        @if(in_array('superadmin_access', $dataLogin))
        <li class="nav-item">
                <a href="{{ url('#') }}" class="nav-link">
                    <i class="link-icon" data-feather="users"></i>
                        <span class="menu-title">Recruitments</span>
                    <i class="link-arrow"></i>
                </a>
                <div class="submenu">
                    <ul class="submenu-item">
                        <li class="nav-item">
                            <a href="{{ route('job-aplicant') }}" 
                               class="nav-link {{ active_class(['job-aplicant']) }}">
                                Job Aplicant
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('test') }}" 
                               class="nav-link {{ active_class(['test']) }}">
                                Test Result
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('medis') }}" 
                               class="nav-link {{ active_class(['medis']) }}">
                                Medis Result
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('logbook-tamu') }}" 
                               class="nav-link {{ active_class(['logbook']) }}">
                                Training
                            </a>
                        </li>
                        
                    </ul>
                </div>
            </li>
        @endif
        @if(in_array('client_access', $dataLogin))
        <li class="nav-item">
                <a href="{{ url('#') }}" class="nav-link">
                    <i class="link-icon" data-feather="book"></i>
                        <span class="menu-title">Logbook</span>
                    <i class="link-arrow"></i>
                </a>
                <div class="submenu">
                    <ul class="submenu-item">
                        <li class="nav-item"><a href="{{ route('logbook-tamu') }}" class="nav-link {{ active_class(['logbook']) }}">Tamu</a></li>
                        <li class="nav-item"><a href="{{ route('logbook-barang') }}" class="nav-link {{ active_class(['component-data']) }}">Barang</a></li>
                    </ul>
                </div>
            </li>
        @endif
        @if(in_array('superadmin_access', $dataLogin)  || in_array('am_access', $dataLogin))
            <li class="nav-item">
                <a href="{{ route('setting.index') }}" class="nav-link {{ active_class(['setting']) }}">
                    <i class="link-icon" data-feather="settings"></i>
                    <span class="menu-title">Apps Settings</span>
                </a>
            </li>
        @endif
      </ul>
    </div>
  </nav>
</div>
<!-- Mobile Menu -->
<div class="row">
    <div class="container">
        <div class="menu-mobile-wrap d-flex justify-content-between">
            <a href="{{url('dashboard')}}" class="text-muted nav-link {{ active_class(['dashboard']) }}">
                <div class="menu-item-nav text-center">
                    <i class="icon-lg" data-feather="home"></i>
                    <p>Home</p>
                </div>
            </a>
            <a href="{{route('attendence-request.create')}}" class="text-muted nav-link {{ active_class(['attendence*']) }}">
                <div class="menu-item-nav text-center">
                    <i class="icon-lg" data-feather="plus-circle"></i>
                    <p>Request</p>
                </div>
            </a>
            <a href="{{ route('mySlip')}}" class="text-muted nav-link {{ active_class(['payslip*','myslip']) }}">
                <div class="menu-item-nav text-center">
                    <i class="icon-lg" data-feather="file-text"></i>
                    <p>My Slip</p>
                </div>
            </a>
            <a href="{{ route('MyProfile', ['nik' => Auth::user()->name])}}" class="text-muted nav-link {{ active_class(['MyProfile*']) }}">
                <div class="menu-item-nav text-center">
                    <i class="icon-lg" data-feather="user"></i>
                    <p>Profile</p>
                </div>
            </a>
        </div>
    </div>
</div>

<script>
function submitForm() {
  document.getElementById("logout_admin").submit();
}
</script>