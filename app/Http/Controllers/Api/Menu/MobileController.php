<?php

namespace App\Http\Controllers\Api\Menu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

use App\Models\Menu\MobileMenu;
use App\Models\Menu\MobileMenuRelatioans;

class MobileController extends Controller
{
    // Retrieve a list of all MobileMenu items
    public function index($company)
    {
        // Explicitly retrieve the 'barrier' session value
        $barrier = session('barrier');

        $records = MobileMenu::orderBy('urutan', 'asc')->get();
        
        if ($records->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No MobileMenu items found',
                'data' => []
            ], 404);
        }

        foreach ($records as $record) {
            $record->created_label = date('l, d F Y', strtotime($record->created_at));
            $cek = MobileMenuRelatioans::where('mobile_menu_id', $record->id)
                ->where('company_name',$company )
                ->count();

            $record->status = $cek > 0 ? 1 : 0; // Set status based on relation count
        }
        return response()->json([
            'status' => 'success',
            'message' => 'MobileMenu items retrieved successfully',
            'barrier' => $barrier, // Include the 'barrier' session value
            'data' => $records
        ], 200);
    }

    // Store a new MobileMenu item
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'menu_name' => 'required|string|max:255',
            'route_link' => 'required|string|max:255',
            'urutan' => 'required|integer',
            'maintenance' => 'required|boolean',
        ]);

        $mobileMenu = MobileMenu::create($validatedData);

        return response()->json([
            'status' => 'success',
            'message' => 'MobileMenu item created successfully',
            'data' => $mobileMenu
        ], 201);
    }

    // Retrieve a specific MobileMenu item by ID
    public function show($id)
    {
        $mobileMenu = MobileMenu::find($id);

        if (!$mobileMenu) {
            return response()->json([
                'status' => 'error',
                'message' => 'MobileMenu not found',
                'data' => null
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'MobileMenu item retrieved successfully',
            'data' => $mobileMenu
        ], 200);
    }

    // Update a specific MobileMenu item by ID
    public function update(Request $request, $id)
    {
        $mobileMenu = MobileMenu::find($id);

        if (!$mobileMenu) {
            return response()->json([
                'status' => 'error',
                'message' => 'MobileMenu not found',
                'data' => null
            ], 404);
        }

        $validatedData = $request->validate([
            'menu_name' => 'required|string|max:255',
            'route_link' => 'required|string|max:255',
            'urutan' => 'required|integer',
            'maintenance' => 'required|boolean',
            'icon' => 'required|string|max:255',
        ]);

        $mobileMenu->update($validatedData);

        return response()->json([
            'status' => 'success',
            'message' => 'MobileMenu item updated successfully',
            'data' => $mobileMenu
        ], 200);
    }

    // Delete a specific MobileMenu item by ID
    public function destroy($id)
    {
        $mobileMenu = MobileMenu::find($id);

        if (!$mobileMenu) {
            return response()->json([
                'status' => 'error',
                'message' => 'MobileMenu not found',
                'data' => null
            ], 404);
        }

        $mobileMenu->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'MobileMenu deleted successfully',
            'data' => null
        ], 200);
    }

    // Change the status of a MobileMenu item
    public function changeStatus(Request $request)
    {
        $validatedData = $request->validate([
            'menu_id' => 'required|integer|exists:mobile_menus,id',
            'unit_bisnis' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);

        if($validatedData['status']==1){
            MobileMenuRelatioans::create([
                'mobile_menu_id' => $validatedData['menu_id'],
                'company_name' => $validatedData['unit_bisnis'],
                'status' => $validatedData['status'],
            ]);
        }else{
            MobileMenuRelatioans::where('mobile_menu_id', $validatedData['menu_id'])
                ->where('company_name', $validatedData['unit_bisnis'])
                ->delete();
        }


        return response()->json([
            'status' => 'success',
            'message' => 'MobileMenu status updated successfully',
        ], 200);
    }
}
