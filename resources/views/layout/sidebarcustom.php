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
    @php
        if (Auth::check()) {
            $roles = Auth::user()->permission; // Role user yang login
            $menus = App\Setting\Features\FeaturesModel::where('is_active', 1)
                ->whereNull('parent_id')
                ->with('children')
                ->orderBy('order')
                ->get();

            // Filter menu berdasarkan role
            $filteredMenus = $menus->filter(function ($menu) use ($roles) {

                $roleIds = is_string($menu->roles) ? json_decode($menu->roles, true) : $menu->roles;
                if (!is_array($roleIds)) {
                    return false; // Abaikan jika roles bukan array
                }
                return in_array($roles, $roleIds); // Periksa apakah role user ada di array
            });
        } else {
            $filteredMenus = collect(); // Kosongkan menu jika tidak ada user login
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
                                        // Jika role_id adalah array, tidak perlu decoding
                                        $childRoleIds = is_array($child->roles) ? $child->roles : json_decode($child->roles, true);
                                    @endphp
                                    @if(is_array($childRoleIds) && in_array($roles, $childRoleIds))
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