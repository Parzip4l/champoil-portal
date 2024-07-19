<?php

namespace App\Http\Controllers\Emergency;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Emergency\EmergencyCategory;
use App\Emergency\EmergencyModel;
use App\Employee;

class EmergencyController extends Controller
{
    public function index()
    {
        // Cek User Login 
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();

        $emergency = EmergencyModel::where('company', $company->unit_bisnis)->get();
        $category = EmergencyCategory::where('company', $company->unit_bisnis)->get();

        return view('pages.emergency.index', compact('emergency','category'));
    }

    public function userpages()
    {
        $code = Auth::user()->employee_code;
        $employee = Employee::where('nik', $code)->first();
        $emergency = EmergencyModel::where('user', $employee->nama)->get();
        $category = EmergencyCategory::where('company', $employee->unit_bisnis)->get();
        return view('pages.user-pages.emergency', compact('employee','category','emergency'));
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $emergency = EmergencyModel::find($id);
            if ($emergency) {
                $emergency->status = $request->status;
                $emergency->save();
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false, 'message' => 'Emergency record not found']);
            }
        } catch (\Exception $e) {
            // Log the exception message
            \Log::error('Error updating status: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred while updating the status']);
        }
    }

    public function CancelRequest(Request $request, $id)
    {
        try {
            $emergency = EmergencyModel::find($id);
            if ($emergency) {
                $emergency->status = $request->status;
                $emergency->save();
                return response()->json(['success' => true]);
            } else {
                return redirect()->back()->with('error', 'Data Tidak Ditemukan');
            }

            return redirect()->back()->with('success', 'Data Berhasil diupdate');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function storeRequest(Request $request)
    {
        $code = Auth::user()->employee_code;
        $employee = Employee::where('nik', $code)->first();
        $request->validate([
            'user' => 'required|string|max:255',
        ]);

        try {
            $emergency = new EmergencyModel();
            $emergency->user = $request->user;
            $emergency->latitude = $request->latitude;
            $emergency->longitude = $request->longitude;
            $emergency->category = $request->kategori;
            $emergency->deskripsi = $request->deskripsi;
            $emergency->status = $request->status;
            $emergency->company = $employee->unit_bisnis;
            $emergency->save();

            return redirect()->back()->with('success', 'Report Berhasil Dikirim');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
