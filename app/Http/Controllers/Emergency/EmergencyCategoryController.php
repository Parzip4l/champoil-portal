<?php

namespace App\Http\Controllers\Emergency;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Emergency\EmergencyCategory;
use App\Emergency\EmergencyModel;
use App\Employee;

class EmergencyCategoryController extends Controller
{

    public function store(Request $request)
    {
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();

        try{
            $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $category = new EmergencyCategory();
            $category->name = $request->name;
            $category->company = $company->unit_bisnis;
            $category->save();

            return redirect()->route('emergency-data.index')->with('success', 'Category Berhasil Ditambahkan');

        }catch(ValidationException $exception){
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try{
            $category = EmergencyCategory::findOrFail($id);
            $category->name = $request->name;
            $category->save();

            return redirect()->route('emergency-data.index')->with('success', 'Category berhasil diupdate');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try{
            $category = EmergencyCategory::findOrFail($id);
            $category->delete();

            return redirect()->route('emergency-data.index')->with('success', 'Category berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
