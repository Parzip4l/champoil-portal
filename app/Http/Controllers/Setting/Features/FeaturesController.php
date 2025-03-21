<?php

namespace App\Http\Controllers\Setting\Features;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Setting\Features\FeaturesModel;
use App\RolesAuthority;

class FeaturesController extends Controller
{
    // 1. Index 

    public function index(Request $request)
    {
        $search = $request->get('search'); 

        // Query untuk mendapatkan data menu dengan filter pencarian jika ada
        $menu = FeaturesModel::when($search, function ($query, $search) {
            return $query->where('title', 'like', '%' . $search . '%');
        })
        ->orderBy('order', 'asc')
        ->paginate(100);

        // Jika permintaan AJAX, kembalikan hanya bagian tampilan yang perlu diperbarui
        if ($request->ajax()) {
            return view('pages.app-setting.features.index', compact('menu'))->render(); 
        }

        // Untuk tampilan biasa, kirimkan data menu
        return view('pages.app-setting.features.index', compact('menu', 'search'));
    }

    // 2. Create

    public function create()
    {   
        $menuData = FeaturesModel::whereNull('parent_id')->get();
        $roles = RolesAuthority::all();
        return view('pages.app-setting.features.create', compact('menuData','roles'));
    }

    // 3. Store
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'url' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:features,id',
            'is_active' => 'required|in:1,0',
            'order' => 'nullable|integer',
        ]);

        try {
            // Simpan menu baru
            $menu = new FeaturesModel();
            $menu->title = $request->title;
            $menu->icon = $request->icon;
            $menu->url = $request->url;
            $menu->parent_id = $request->parent_id;
            $menu->is_active = $request->is_active;
            $menu->order = $request->order;
            $menu->roles = $request->role_ids;
            $menu->save();

            // Redirect dengan pesan sukses
            return redirect()->route('features-management.index')->with('success', 'Features created successfully.');
        } catch (\Exception $e) {
            // Log error dan kembalikan error response
            return back()->with('error', 'Failed to create menu. Please try again later.' . $e->getMessage());
        }
    }

    // 4. Edit
    public function edit($id)
    {
        try {
            $menu = FeaturesModel::findOrFail($id);
            // Ambil data menu parent untuk dropdown
            $menuData = FeaturesModel::whereNull('parent_id')->get();
            $roles = RolesAuthority::all();

            return view('pages.app-setting.features.edit', compact('menu', 'menuData','roles'));
        } catch (Exception $e) {
            // Jika terjadi error, tampilkan pesan error
            return redirect()->route('pages.app-setting.features.index')->with('error', 'Menu not found!');
        }
    }

    // 5. Update
    public function update(Request $request, $id)
    {
        try {
            // Validasi input
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'icon' => 'nullable|string|max:255',
                'url' => 'nullable|string|max:255',
                'parent_id' => 'nullable|exists:features,id',
                'is_active' => 'required|boolean',
                'order' => 'required|integer',
            ]);

            // Update data menu
            $menu = FeaturesModel::findOrFail($id);
            $menu->title = $validated['title'];
            $menu->icon = $validated['icon'];
            $menu->url = $validated['url'];
            $menu->parent_id = $validated['parent_id'];
            $menu->is_active = $validated['is_active'];
            $menu->order = $validated['order'];
            $menu->roles = $request->role_ids;
            $menu->save();

            // Redirect dengan pesan sukses
            return redirect()->route('features-management.index')->with('success', 'Menu updated successfully!');
        } catch (Exception $e) {
            // Jika terjadi error, tampilkan pesan error
            return redirect()->route('features-management.index')->with('error', 'An error occurred while updating the menu.');
        }
    }

    // 6. Delete
    public function destroy($id)
    {
        try {
            // Cari menu berdasarkan ID
            $menu = FeaturesModel::findOrFail($id);

            // Hapus menu
            $menu->delete();

            // Mengembalikan response JSON dengan status sukses
            return response()->json([
                'success' => true,
                'message' => 'Menu has been deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete menu. Please try again later. ' . $e->getMessage()
            ], 500); // Menggunakan status code 500 jika ada error server
        }
    }

    public function updateStatus(Request $request, $id)
    {

        // Validasi data yang dikirim
        $request->validate([
            'is_active' => 'required|boolean',
        ]);
        
        try {
            $data = FeaturesModel::findOrFail($id);

            // Perbarui status is_active
            $data->is_active = $request->is_active;
            $data->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {

            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
