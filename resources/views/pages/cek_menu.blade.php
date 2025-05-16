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
    print("<pre>".print_r($filteredMenus,true)."</pre>");

    print_r($dataLogin);
@endphp
