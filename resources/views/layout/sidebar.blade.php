<nav class="sidebar">
    <div class="sidebar-header">
        <img src="{{ url('assets/images/logo/logodesktop.png') }}" alt="TRUEST Logo" style="width:50%;">
        <div class="sidebar-toggler not-active">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    @php 
        $user = Auth::user();
        $dataLogin = json_decode(Auth::user()->permission); 
        $employee = \App\Employee::where('nik', Auth::user()->name)->first(); 
    @endphp
    <div class="sidebar-body">
        <ul class="nav">
            <li class="nav-item nav-category">Main</li>
            @if(in_array('superadmin_access', $dataLogin) || in_array('client_access', $dataLogin) || in_array('sales_access', $dataLogin) || in_array('dashboard_access', $dataLogin))
            <li class="nav-item {{ active_class(['/dashboard']) }}">
                <a href="{{ url('/dashboard') }}" class="nav-link">
                    <i class="link-icon" data-feather="pie-chart"></i>
                    <span class="link-title">Dashboard</span>
                </a>
            </li>
            @endif
            @if(in_array('superadmin_access', $dataLogin)|| in_array('client_access', $dataLogin))
            <li class="nav-item nav-category">Employee</li>
            <li class="nav-item {{ active_class(['employee']) }}">
                <a href="{{ url('/employee') }}" class="nav-link">
                    <i class="link-icon" data-feather="users"></i>
                    <span class="link-title">Employee </span>
                </a>
            </li>
            <li class="nav-item {{ active_class(['employee-resign']) }}">
                <a href="{{ url('/employee-resign') }}" class="nav-link">
                    <i class="link-icon" data-feather="user-x"></i>
                    <span class="link-title">Employee Resign</span>
                </a>
            </li>
            @endif
            @if(in_array('superadmin_access', $dataLogin) || in_array('am_access', $dataLogin) || in_array('admin_access', $dataLogin))
            <li class="nav-item nav-category">Time Management</li>
                <li class="nav-item {{ active_class(['']) }}">
                    <a class="nav-link" data-bs-toggle="collapse" href="#timemanagement" role="button" aria-expanded="{{ is_active_route(['timemanagement']) }}" aria-controls="timemanagement">
                        <i class="link-icon" data-feather="database"></i>
                        <span class="link-title">Master</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse {{ show_class(['data']) }}" id="timemanagement">
                        <ul class="nav sub-menu">
                            <li class="nav-item">
                                <a href="{{ url('absen') }}" class="nav-link {{ active_class(['absen']) }}">Attendance Record</a>
                            </li>
                            @if($employee && $employee->unit_bisnis == 'Kas' && $user->project_id == NULL)
                                <li class="nav-item">
                                    <a href="{{ route('backup.log') }}" class="nav-link {{ active_class(['ckup-log']) }}">Backup Record</a>
                                </li>
                            @endif
                            @if($employee && $employee->unit_bisnis == 'Kas')
                                <li class="nav-item"><a href="{{ route('schedule.index') }}" class="nav-link {{ active_class(['kas/schedule']) }}">Schedule</a></li>
                                @if($user->project_id == NULL)
                                <li class="nav-item"><a href="{{ url('kas/backup-schedule') }}" class="nav-link {{ active_class(['kas/backup-schedule']) }}">Backup Schedule</a></li>
                                @endif
                            @endif
                            @if($user->project_id == NULL)
                                <li class="nav-item"><a href="{{ route('shift.index') }}" class="nav-link {{ active_class(['shift']) }}">Shift</a></li>
                            @endif
                            @if($employee && $employee->unit_bisnis == 'Kas'  && $user->project_id == NULL)
                                <li class="nav-item"><a href="{{ url(route('report.index') . '?periode='.date('M-Y')) }}" class="nav-link {{ active_class(['kas/report']) }}">Report</a></li>
                            @endif
                        </ul>
                    </div>
                </li>
                @if(in_array('pic_access', $dataLogin) || in_array('superadmin_access', $dataLogin) || in_array('am_access', $dataLogin)|| in_array('client_access', $dataLogin))
                <li class="nav-item">
                    <a href="{{ route('pengajuan-schedule.index') }}" class="nav-link {{ active_class(['kas/pengajuan-schedule']) }}">
                        <i class="link-icon" data-feather="calendar"></i>
                        <span class="link-title">Pengajuan Schedule</span>
                    </a>
                </li>
                @endif
            @endif
            @if(in_array('superadmin_access', $dataLogin))
            <li class="nav-item nav-category">Payrol</li>
            <li class="nav-item {{ active_class(['payroll']) }}">
                <a href="{{ url('/payroll') }}" class="nav-link">
                    <i class="link-icon" data-feather="dollar-sign"></i>
                    <span class="link-title">Payrol Data</span>
                </a>
            </li>
            <li class="nav-item {{ active_class(['payslip']) }}">
                <a href="{{ url('/payslip') }}" class="nav-link">
                    <i class="link-icon" data-feather="bar-chart"></i>
                    <span class="link-title">Payrol History</span>
                </a>
            </li>
            @if($employee && $employee->unit_bisnis == 'CHAMPOIL')
            <li class="nav-item {{ active_class(['payroll.ns']) }}">
                <a href="{{ route('payroll.ns') }}" class="nav-link">
                    <i class="link-icon" data-feather="dollar-sign"></i>
                    <span class="link-title">Payrol Frontline</span>
                </a>
            </li>
            @endif
            @if($employee && $employee->unit_bisnis == 'Kas')
            <li class="nav-item {{ active_class(['payroll-kas.index']) }}">
                <a href="{{ route('payroll-kas.index') }}" class="nav-link">
                    <i class="link-icon" data-feather="dollar-sign"></i>
                    <span class="link-title">Payrol Frontline</span>
                </a>
            </li>
            @endif
            <li class="nav-item {{ active_class(['payrol-component']) }}">
                <a href="{{ url('/payrol-component') }}" class="nav-link">
                    <i class="link-icon" data-feather="user-plus"></i>
                    <span class="link-title">Assign Component</span>
                </a>
            </li>
            <li class="nav-item {{ active_class(['component-data']) }}">
                <a href="{{ url('/component-data') }}" class="nav-link">
                    <i class="link-icon" data-feather="settings"></i>
                    <span class="link-title">Component Master</span>
                </a>
            </li>
            @endif
            @if(in_array('superadmin_access', $dataLogin))
            <li class="nav-item nav-category">Koperasi</li>
            <li class="nav-item {{ active_class(['koperasi']) }}">
                <a href="{{ url('/koperasi') }}" class="nav-link">
                    <i class="link-icon" data-feather="users"></i>
                    <span class="link-title">Koperasi</span>
                </a>
            </li>
            @endif
            @if(in_array('superadmin_access', $dataLogin) || in_array('am_access', $dataLogin)|| in_array('client_access', $dataLogin))
            <li class="nav-item nav-category">Task Management</li>
            <li class="nav-item">
                <a href="{{ route('knowledge_base.index') }}" class="nav-link">
                    <i class="link-icon" data-feather="book-open"></i>
                    <span class="link-title">LMS</span>
                </a>
            </li>
            <li class="nav-item {{ active_class(['']) }}">
                <a class="nav-link" data-bs-toggle="collapse" href="#task_management" role="button" aria-expanded="{{ is_active_route(['task_management']) }}" aria-controls="task_management">
                    <i class="link-icon" data-feather="clock"></i>
                    <span class="link-title">Task Management</span>
                    <i class="link-arrow" data-feather="chevron-down"></i>
                </a>
                <div class="collapse {{ show_class(['category']) }}" id="task_management">
                    <ul class="nav sub-menu">
                        <li class="nav-item">
                            <a href="{{ url('task') }}" class="nav-link {{ active_class(['task']) }}">Patroli</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('') }}" class="nav-link {{ active_class(['']) }}">Audit</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('taskg.index') }}" class="nav-link {{ active_class(['taskg.index']) }}">Task</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item {{ active_class(['']) }}">
                <a class="nav-link" data-bs-toggle="collapse" href="#logBook" role="button" aria-expanded="{{ is_active_route(['logBook']) }}" aria-controls="logBook">
                    <i class="link-icon" data-feather="book-open"></i>
                    <span class="link-title">Log Book</span>
                    <i class="link-arrow" data-feather="chevron-down"></i>
                </a>
                <div class="collapse {{ show_class(['category']) }}" id="logBook">
                    <ul class="nav sub-menu">
                        <li class="nav-item">
                            <a href="{{ url('logbook-tamu') }}" class="nav-link {{ active_class(['logbook-tamu']) }}">Tamu</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('logbook-barang') }}" class="nav-link {{ active_class(['logbook-barang']) }}">Barang</a>
                        </li>
                    </ul>
                </div>
            </li>
            @endif
            @if(in_array('superadmin_access', $dataLogin))
            <li class="nav-item nav-category">Reqruitment</li>
            <li class="nav-item {{ active_class(['']) }}">
                <a class="nav-link" data-bs-toggle="collapse" href="#reqruitment" role="button" aria-expanded="{{ is_active_route(['product-category']) }}" aria-controls="reqruitment">
                    <i class="link-icon" data-feather="settings"></i>
                    <span class="link-title">Master Data</span>
                    <i class="link-arrow" data-feather="chevron-down"></i>
                </a>
                <div class="collapse {{ show_class(['category']) }}" id="reqruitment">
                    <ul class="nav sub-menu">
                        <li class="nav-item">
                            <a href="{{ route('job-aplicant') }}" class="nav-link {{ active_class(['job-aplicant']) }}">Job Aplicant</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('test') }}" class="nav-link {{ active_class(['test']) }}">Result Test</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('medis') }}" class="nav-link {{ active_class(['medis']) }}">Medis Result</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('logbook-tamu') }}" class="nav-link {{ active_class(['logbook-tamu']) }}">Training Data</a>
                        </li>
                    </ul>
                </div>
            </li>
            @endif
            @if(in_array('superadmin_access', $dataLogin))
            <li class="nav-item nav-category">Settings</li>
            <li class="nav-item">
                <a href="{{route('setting.index')}}" class="nav-link {{ active_class(['setting']) }}">
                    <i class="link-icon" data-feather="hash"></i>
                    <span class="link-title">App Setting</span>
                </a>
            </li>
            @endif
        </ul>
    </div>
</nav>