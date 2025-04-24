<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Model
use App\Employee;
use App\RolesAuthority;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();

        $search = $request->get('search'); 

        $role = RolesAuthority::where('company',$company->unit_bisnis)->when($search, function ($query, $search) {
            return $query->where('role_name', 'like', '%' . $search . '%');
        })
        ->paginate(10);

        if ($request->ajax()) {
            return view('pages.app-setting.roles.index', compact('role'))->render(); 
        }

        return view('pages.app-setting.roles.index', compact('role'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.app-setting.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();

        $validated = $request->validate([
            'role_name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'name' => 'required|string|max:255',
        ]);

        try {
            // Simpan role baru
            $role = new RolesAuthority();
            $role->role_name = $request->role_name;
            $role->description = $request->description;
            $role->name = $request->name;
            $role->company = $company->unit_bisnis;
            $role->save();

            // Redirect dengan pesan sukses
            return redirect()->route('roles.index')->with('success', 'Roles created successfully.');
        } catch (\Exception $e) {
            // Log error dan kembalikan error response
            return back()->with('error', 'Failed to create role. Please try again later.' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            // Ambil data menu berdasarkan id
            $roles = RolesAuthority::findOrFail($id);

            return view('pages.app-setting.roles.edit', compact('roles'));
        } catch (Exception $e) {
            // Jika terjadi error, tampilkan pesan error
            return redirect()->route('roles.index')->with('error', 'Menu not found!');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            // Validasi input
            $validated = $request->validate([
                'role_name' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'name' => 'required|string|max:255',
            ]);

            // Update data menu
            $role = RolesAuthority::findOrFail($id);
            $role->role_name = $validated['role_name'];
            $role->description = $validated['description'];
            $role->name = $validated['name'];
            $role->save();

            // Redirect dengan pesan sukses
            return redirect()->route('roles.index')->with('success', 'role updated successfully!');
        } catch (Exception $e) {
            // Jika terjadi error, tampilkan pesan error
            return redirect()->route('roles.index')->with('error', 'An error occurred while updating the role.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            // Cari menu berdasarkan ID
            $role = RolesAuthority::findOrFail($id);

            // Hapus menu
            $role->delete();

            // Mengembalikan response JSON dengan status sukses
            return response()->json([
                'success' => true,
                'message' => 'Menu has been deleted successfully.'
            ]);
        } catch (\Exception $e) {
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete menu. Please try again later. ',
            ], 500); // Menggunakan status code 500 jika ada error server
        }
    }
}
