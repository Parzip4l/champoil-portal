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
            @if($employee && $employee->unit_bisnis == 'NOTARIS_ITR')
                @if(in_array('superadmin_access', $dataLogin))
                <li class="nav-item {{ active_class(['employee']) }}">
                    <a href="{{ url('employee') }}" class="nav-link">
                        <i class="link-icon" data-feather="file-text"></i>
                        <span class="link-title">Data Karyawan</span>
                    </a>
                </li>
                @endif
                <li class="nav-item {{ active_class(['customer']) }}">
                    <a href="{{ route('customer.index') }}" class="nav-link">
                        <i class="link-icon" data-feather="users"></i>
                        <span class="link-title">Customer Data</span>
                    </a>
                </li>
                <li class="nav-item {{ active_class(['invoice']) }}">
                    <a href="{{ route('invoice.index') }}" class="nav-link">
                        <i class="link-icon" data-feather="file-text"></i>
                        <span class="link-title">Invoice</span>
                    </a>
                </li>
                <li class="nav-item {{ active_class(['buku-kas']) }}">
                    <a href="{{ route('buku-kas.index') }}" class="nav-link">
                        <i class="link-icon" data-feather="file"></i>
                        <span class="link-title">Laporan Kas</span>
                    </a>
                </li>
            @endif

            <!-- Employee Data -->
            @if($employee && $employee->unit_bisnis != 'NOTARIS_ITR')
                @if(in_array('superadmin_access', $dataLogin)|| in_array('client_access', $dataLogin) || in_array('am_access', $dataLogin) || in_array('admin_access', $dataLogin) || in_array('hr_frontline', $dataLogin))
                <li class="nav-item nav-category">Employee</li>
                <li class="nav-item {{ active_class(['']) }}">
                    <a class="nav-link" data-bs-toggle="collapse" href="#timemanagement" role="button" aria-expanded="{{ is_active_route(['timemanagement']) }}" aria-controls="timemanagement">
                        <i class="link-icon" data-feather="clock"></i>
                        <span class="link-title">Time Management</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse {{ show_class(['data']) }}" id="timemanagement">
                        <ul class="nav sub-menu">
                            <li class="nav-item">
                                <a href="{{ url('absen') }}" class="nav-link {{ active_class(['absen']) }}">Attendance Record</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('duplikat-absen') }}" class="nav-link {{ active_class(['duplikat-absen']) }}">Duplikat Record</a>
                            </li>
                            @if($employee && $employee->unit_bisnis == 'Kas' && $user->project_id == NULL)
                                <li class="nav-item">
                                    <a href="{{ route('backup.log') }}" class="nav-link {{ active_class(['ckup-log']) }}">Backup Record</a>
                                </li>
                            @endif
                            @if($employee && $employee->unit_bisnis == 'Kas' || $employee->unit_bisnis == 'RUN' || $employee->unit_bisnis == 'Run')
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
                            @if(in_array('pic_access', $dataLogin) || in_array('superadmin_access', $dataLogin) || in_array('am_access', $dataLogin) || in_array('admin_access', $dataLogin) || in_array('client_access', $dataLogin))
                            <li class="nav-item">
                                <a href="{{ route('pengajuan-schedule.index') }}" class="nav-link {{ active_class(['kas/pengajuan-schedule']) }}">Pengajuan Schedule</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('attendence-request.index') }}" class="nav-link {{ active_class(['attendance/attendence-request']) }}">Attendance Request</a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </li>
                <!-- Employee Menu -->
                <li class="nav-item {{ active_class(['']) }}">
                    <a class="nav-link" data-bs-toggle="collapse" href="#employee_management" role="button" aria-expanded="{{ is_active_route(['employee_management']) }}" aria-controls="employee_management">
                        <i class="link-icon" data-feather="users"></i>
                        <span class="link-title">Employee Data</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse {{ show_class(['data']) }}" id="employee_management">
                        <ul class="nav sub-menu">
                            <li class="nav-item">
                                <a href="{{ url('employee') }}" class="nav-link {{ active_class(['employee']) }}">Employee Data</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('employee-resign') }}" class="nav-link {{ active_class(['employee-resign']) }}">Employee Resign</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('map-frontline') }}" class="nav-link {{ active_class(['map-frontline']) }}">Employee Home Maps</a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif
            @endif
            <!-- Performance Appraisal Menu -->
            @if($employee && $employee->unit_bisnis != 'NOTARIS_ITR')
                @if(in_array('superadmin_access', $dataLogin)|| in_array('dashboard_access', $dataLogin))
                <li class="nav-item {{ active_class(['']) }}">
                    <a class="nav-link" data-bs-toggle="collapse" href="#performance" role="button" aria-expanded="{{ is_active_route(['performance']) }}" aria-controls="performance">
                        <i class="link-icon" data-feather="file-text"></i>
                        <span class="link-title">Performance Master</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse {{ show_class(['data']) }}" id="performance">
                        <ul class="nav sub-menu">
                            <li class="nav-item">
                                <a href="{{ route('index.pa') }}" class="nav-link {{ active_class(['performance-appraisal/performance-master']) }}">Performance Appraisal</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url('performance-appraisal/my-performance')}}" class="nav-link {{ active_class(['performance-appraisal/my-performance']) }}">My Performance Appraisal</a>
                            </li>
                            @if(in_array('superadmin_access', $dataLogin))
                            <li class="nav-item">
                                <a href="{{ route('setting.pa') }}" class="nav-link {{ active_class(['performance-appraisal/setting']) }}">Settings</a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </li>
                @endif
            @endif
            <!-- Reqruitment -->
            @if(in_array('superadmin_access', $dataLogin) || in_array('bd_access', $dataLogin) || in_array('hr_frontline', $dataLogin))
                @if($employee && $employee->unit_bisnis == 'Kas')
                <li class="nav-item {{ active_class(['']) }}">
                    <a class="nav-link" data-bs-toggle="collapse" href="#reqruitment" role="button" aria-expanded="{{ is_active_route(['product-category']) }}" aria-controls="reqruitment">
                        <i class="link-icon" data-feather="user-plus"></i>
                        <span class="link-title">Reqruitment</span>
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
                            <!-- <li class="nav-item">
                                <a href="{{ route('logbook-tamu') }}" class="nav-link {{ active_class(['logbook-tamu']) }}">Training Data</a>
                            </li> -->
                            <li class="nav-item">
                                <a href="{{ route('penempatan') }}" class="nav-link {{ active_class(['penempatan']) }}">Penempatan</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('dashboard-recruitment') }}" class="nav-link {{ active_class(['dashboard-recruitment']) }}">Report</a>
                            </li>   
                        </ul>
                    </div>
                </li>
                @endif
            @endif
            @if($employee && $employee->unit_bisnis != 'NOTARIS_ITR')
                @if(in_array('superadmin_access', $dataLogin))
                <!-- News And Announcement -->
                <li class="nav-item {{ active_class(['/pengumuman']) }}">
                    <a href="{{ url('/pengumuman') }}" class="nav-link">
                        <i class="link-icon" data-feather="mic"></i>
                        <span class="link-title">Announcement</span>
                    </a>
                </li>
                <li class="nav-item {{ active_class(['/news']) }}">
                    <a href="{{ url('/news') }}" class="nav-link">
                        <i class="link-icon" data-feather="file-text"></i>
                        <span class="link-title">News</span>
                    </a>
                </li>
                @endif
            @endif
            <!-- Payroll Menu -->
            @if(in_array('superadmin_access', $dataLogin) || in_array('hr_frontline', $dataLogin))
            <li class="nav-item nav-category">Payrol & Koperasi</li>
            <li class="nav-item {{ active_class(['']) }}">
                <a class="nav-link" data-bs-toggle="collapse" href="#PayrolMaster" role="button" aria-expanded="{{ is_active_route(['asset-management']) }}" aria-controls="PayrolMaster">
                    <i class="link-icon" data-feather="dollar-sign"></i>
                    <span class="link-title">Payrol Master</span>
                    <i class="link-arrow" data-feather="chevron-down"></i>
                </a>
                <div class="collapse {{ show_class(['category']) }}" id="PayrolMaster">
                    <ul class="nav sub-menu">
                    @if(in_array('superadmin_access', $dataLogin))
                        <li class="nav-item">
                            <a href="{{ url('payroll') }}" class="nav-link {{ active_class(['payroll']) }}">Payrol Data</a>
                        </li>
                    @endif
                        <li class="nav-item">
                            <a href="{{ url('employee-loan') }}" class="nav-link {{ active_class(['employee-loan']) }}">Loan</a>
                        </li>
                        
                        <li class="nav-item">
                            <a href="{{ url('payslip') }}" class="nav-link {{ active_class(['payslip']) }}">Payrol History</a>
                        </li>
                        @if($employee && $employee->unit_bisnis == 'CHAMPOIL')
                        <li class="nav-item">
                            <a href="{{ route('payroll.ns') }}" class="nav-link {{ active_class(['payroll.ns']) }}">Payrol Frontline</a>
                        </li>
                        @endif
                        @if($employee && $employee->unit_bisnis == 'Run')
                        <li class="nav-item">
                            <a href="{{ route('payroll.ns') }}" class="nav-link {{ active_class(['payroll.ns']) }}">Payrol Frontline</a>
                        </li>
                        @endif
                        @if($employee && $employee->unit_bisnis == 'Kas')
                        <li class="nav-item">
                            <a href="{{ route('payroll-kas.index') }}" class="nav-link {{ active_class(['payroll-kas.index']) }}">Payrol Frontline</a>
                        </li>
                        @endif
                        <li class="nav-item">
                            <a href="{{ url('payrol-component') }}" class="nav-link {{ active_class(['payrol-component']) }}">Assign Component</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('component-data') }}" class="nav-link {{ active_class(['component-data']) }}">Component Master</a>
                        </li>
                    </ul>
                </div>
            </li>
                @if($employee && $employee->unit_bisnis != 'NOTARIS_ITR')
                    <li class="nav-item {{ active_class(['koperasi']) }}">
                        <a href="{{ url('/koperasi') }}" class="nav-link">
                            <i class="link-icon" data-feather="users"></i>
                            <span class="link-title">Koperasi</span>
                        </a>
                    </li>
                @endif
            @endif
            <!-- End Payrol -->

            <!-- Task & Report Menu -->
            @if(in_array('superadmin_access', $dataLogin) || in_array('am_access', $dataLogin) || in_array('dashboard_access', $dataLogin) || in_array('client_access', $dataLogin))
            <li class="nav-item nav-category">Task & Report </li>
            <li class="nav-item">
                <a href="{{ route('knowledge_base.index') }}" class="nav-link">
                    <i class="link-icon" data-feather="book-open"></i>
                    <span class="link-title">LMS</span>
                </a>
            </li>
            <li class="nav-item {{ active_class(['folders']) }}">
                <a href="{{ route('folders.index') }}" class="nav-link">
                    <i class="link-icon" data-feather="folder"></i>
                    <span class="link-title">Document Controls</span>
                </a>
            </li>
            <li class="nav-item {{ active_class(['']) }}">
                <a class="nav-link" data-bs-toggle="collapse" href="#task_management" role="button" aria-expanded="{{ is_active_route(['task_management']) }}" aria-controls="task_management">
                    <i class="link-icon" data-feather="check-circle"></i>
                    <span class="link-title">Task Management</span>
                    <i class="link-arrow" data-feather="chevron-down"></i>
                </a>
                <div class="collapse {{ show_class(['category']) }}" id="task_management">
                    <ul class="nav sub-menu">
                        @if($employee && $employee->unit_bisnis == 'Kas')
                        <li class="nav-item">
                            <a href="{{ url('task') }}" class="nav-link {{ active_class(['task']) }}">Patroli</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('') }}" class="nav-link {{ active_class(['']) }}">Audit</a>
                        </li>
                        @endif
                        <li class="nav-item">
                            <a href="{{ route('task-management.index') }}" class="nav-link {{ active_class(['task-management.index']) }}">Task</a>
                        </li>
                    </ul>
                </div>
            </li>
            @if($employee && $employee->unit_bisnis == 'Kas')
            <li class="nav-item {{ active_class(['']) }}">
                <a class="nav-link" data-bs-toggle="collapse" href="#logBook" role="button" aria-expanded="{{ is_active_route(['logBook']) }}" aria-controls="logBook">
                    <i class="link-icon" data-feather="book"></i>
                    <span class="link-title">TRACK</span>
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
            @endif
            @if(in_array('superadmin_access', $dataLogin) || in_array('am_access', $dataLogin))
                @if($employee && $employee->unit_bisnis == 'Kas')
                <li class="nav-item {{ active_class(['']) }}">
                    <a class="nav-link" data-bs-toggle="collapse" href="#dmaic" role="button" aria-expanded="{{ is_active_route(['product-category']) }}" aria-controls="reqruitment">
                        <i class="link-icon" data-feather="file-text"></i>
                        <span class="link-title">DMAIC Master</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse {{ show_class(['category']) }}" id="dmaic">
                        <ul class="nav sub-menu">
                            <li class="nav-item">
                                <a href="{{ route('dmaic-report') }}" class="nav-link {{ active_class(['job-aplicant']) }}">Report Data</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('test') }}" class="nav-link {{ active_class(['test']) }}">Category</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('medis') }}" class="nav-link {{ active_class(['medis']) }}">DMAIC List</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('logbook-tamu') }}" class="nav-link {{ active_class(['logbook-tamu']) }}">Analytic</a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif
            @endif
            <!-- Report DMAIC -->
            
            @if(in_array('superadmin_access', $dataLogin) || in_array('bd_access', $dataLogin))
                @if($employee && $employee->unit_bisnis == 'Kas')
                <!-- Maps Project -->
                <li class="nav-item">
                    <a href="{{route('map')}}" class="nav-link {{ active_class(['map']) }}">
                        <i class="link-icon" data-feather="map"></i>
                        <span class="link-title">Maps Project</span>
                    </a>
                </li>
                @endif
            @endif
            <!-- Assets Management -->
            @if($employee && $employee->unit_bisnis != 'NOTARIS_ITR')
                @if(in_array('superadmin_access', $dataLogin) || in_array('am_access', $dataLogin)|| in_array('client_access', $dataLogin)|| in_array('sc_access', $dataLogin))
                <li class="nav-item nav-category">E-SCM </li>
                <li class="nav-item {{ active_class(['']) }}">
                    <a class="nav-link" data-bs-toggle="collapse" href="#AssetManagement" role="button" aria-expanded="{{ is_active_route(['asset-management']) }}" aria-controls="AssetManagement">
                        <i class="link-icon" data-feather="box"></i>
                        <span class="link-title">Assets Management</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse {{ show_class(['category']) }}" id="AssetManagement">
                        <ul class="nav sub-menu">
                            <li class="nav-item">
                                <a href="{{ route('asset.index') }}" class="nav-link {{ active_class(['asset-management/asset']) }}">Asset</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('asset-stock.index') }}" class="nav-link {{ active_class(['asset-management/asset-stock']) }}">Asset Stok</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('asset-category.index') }}" class="nav-link {{ active_class(['asset-management/asset-category']) }}">Asset Kategori</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('asset-vendor.index') }}" class="nav-link {{ active_class(['asset-management/asset-vendor']) }}">Vendor Data</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('') }}" class="nav-link {{ active_class(['']) }}">Transaksi</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('') }}" class="nav-link {{ active_class(['']) }}">Transaksi Histori</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('pengajuan-asset') }}" class="nav-link {{ active_class(['']) }}">Pengajuan Cicilan HP</a>
                            </li>
                        </ul>
                    </div>
                </li>
                
                @endif
            @endif
            @if($employee && $employee->unit_bisnis != 'KAS')
                <li class="nav-item">
                    <a href="{{route('voice.index')}}" class="nav-link {{ active_class(['voice.index']) }}">
                        <i class="link-icon" data-feather="message-circle"></i>
                        <span class="link-title">Voice Of Guardians</span>
                    </a>
                </li>
            @endif
            
            <!-- Apps Settings -->
            @if(in_array('superadmin_access', $dataLogin)  || in_array('hr_frontline', $dataLogin))
            <li class="nav-item nav-category">Settings</li>
                <li class="nav-item">
                    <a href="{{route('setting.index')}}" class="nav-link {{ active_class(['setting']) }}">
                        <i class="link-icon" data-feather="lock"></i>
                        <span class="link-title">App Setting</span>
                    </a>
                </li>
            </li>
            @endif
        </ul>
    </div>
</nav>