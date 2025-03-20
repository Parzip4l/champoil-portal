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
        use App\Setting\Features\FeaturesModel;
        use App\Setting\Features\CompanyFeatures;
        use Illuminate\Support\Facades\Auth;

        $user = Auth::user();
        $dataLogin = json_decode($user->permission, true); // Pastikan permission user berupa array
        $employee = \App\Employee::where('nik', $user->name)->first(); 

        if (Auth::check()) {
            // Ambil ID perusahaan dari data karyawan
            $companyId = $employee->unit_bisnis ?? null;
            // Ambil fitur yang aktif untuk perusahaan
            $activeFeatureIds = CompanyFeatures::where('company_id', $companyId)
                ->where('is_enabled', 1)
                ->pluck('feature_id');

            $menus = FeaturesModel::where('is_active', 1)
                ->whereNull('parent_id')
                ->whereIn('id', $activeFeatureIds) // Filter berdasarkan fitur yang aktif di perusahaan
                ->with(['children' => function ($query) use ($activeFeatureIds) {
                    $query->whereIn('id', $activeFeatureIds);
                }])
                ->orderBy('order')
                ->get();

            // Filter menu berdasarkan role user
            $filteredMenus = $menus->filter(function ($menu) use ($dataLogin) {
                $roleIds = is_string($menu->roles) ? json_decode($menu->roles, true) : $menu->roles;

                return is_array($roleIds) && array_intersect($dataLogin, $roleIds);
            });
        } else {
            $filteredMenus = collect(); // Kosongkan menu jika user belum login
        }
    @endphp


    <div class="sidebar-body">
        @if($filteredMenus->isNotEmpty())
            <ul class="nav" id="navbar-nav">
                @foreach($filteredMenus as $menu)
                    @if($menu->children->isEmpty())
                        <li class="nav-item {{ Request::routeIs($menu->url) ? 'active' : '' }}">
                            <a class="nav-link" href="{{ $menu->url ? route($menu->url) : '#' }}">
                                <i data-feather="{{ $menu->icon }}" class="link-icon"></i>
                                <span class="link-title">{{ $menu->title }}</span>
                            </a>
                        </li>
                    @endif

                    @if($menu->children->isNotEmpty())
                        <li class="nav-item {{ Request::routeIs($menu->url) || $menu->children->contains(fn($child) => Request::routeIs($child->url)) ? 'active' : '' }}">
                            <a class="nav-link" data-bs-toggle="collapse" href="#subitem{{ $menu->id }}" role="button" aria-expanded="false" aria-controls="subitem{{ $menu->id }}">
                                <i class="link-icon" data-feather="{{ $menu->icon }}"></i>
                                <span class="link-title">{{$menu->title}}</span>
                                <i class="link-arrow" data-feather="chevron-down"></i>
                            </a>
                            <div class="collapse {{ show_class(['data']) }}" id="subitem{{ $menu->id }}">
                                <ul class="nav sub-menu">
                                    @foreach($menu->children as $child)
                                        @if($child->is_active === 1)
                                            @php
                                                $childRoleIds = is_string($child->roles) ? json_decode($child->roles, true) : $child->roles;
                                            @endphp
                                            @if(is_array($childRoleIds) && array_intersect($dataLogin, $childRoleIds))
                                                <li class="nav-item {{ Request::routeIs($child->url) ? 'active' : '' }}">
                                                    <a class="nav-link" href="{{ route($child->url) }}">{{ $child->title }}</a>
                                                </li>
                                            @endif
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </li>
                    @endif
                @endforeach
            </ul>
        @endif
    </div>
</nav>