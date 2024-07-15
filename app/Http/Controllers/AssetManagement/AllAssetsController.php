<?php

namespace App\Http\Controllers\AssetManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

// Model
use App\AssetManagement\MasterAsset;
use App\AssetManagement\CategoryAsset;
use App\AssetManagement\StockAsset;
use App\AssetManagement\TransaksiAsset;
use App\AssetManagement\TranskasiHistory;
use App\AssetManagement\VendorAsset;
use App\Employee;

class AllAssetsController extends Controller
{

    // Asset Master Start
    public function IndexAsset()
    {
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();

        $asset = MasterAsset::where('company', $company->unit_bisnis)->get();
        $kategori = CategoryAsset::where('company', $company->unit_bisnis)->get();

        return view('pages.asset-management.index', compact('asset','kategori'));
    }

    public function StoreAsset(Request $request)
    {
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $lastAsset = MasterAsset::orderBy('created_at', 'desc')->first();

        if ($lastAsset) {
            $lastNumber = intval(str_replace('asset_', '', $lastAsset->unix_code));
            $nextNumber = $lastNumber + 000001;
        } else {
            $nextNumber = 1;
        }

        $newUnixCode = 'asset_' . $nextNumber;

        try {
            $asset = new MasterAsset();
            $asset->unix_code = $newUnixCode;
            $asset->name = $request->name;
            $asset->category = $request->category;
            $asset->company = $company->unit_bisnis;
            $asset->save();

            return redirect()->route('asset.index')->with('success', 'Asset berhasil dibuat');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function UpdateAsset(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            $asset = MasterAsset::findOrFail($id);
            $asset->name = $request->name;
            $asset->category = $request->category;
            $asset->save();

            return redirect()->route('asset.index')->with('success', 'Asset berhasil diupdate');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function DestroyAsset($id)
    {
        try {
            $asset = MasterAsset::findOrFail($id);
            $asset->delete();
            return redirect()->route('asset.index')->with('success', 'Asset berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Assets Master End

    // Stock Controller Start
    public function StockIndex()
    {
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();

        $assetStock = StockAsset::where('company', $company->unit_bisnis)->get();
        $assetData = MasterAsset::where('company', $company->unit_bisnis)->get();

        return view('pages.asset-management.stock', compact('assetStock','assetData'));
    }

    public function StockStore(Request $request)
    {
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();

        $request->validate([
            'asset_id' => 'required',
            'qty' => 'required|integer',
            'vendor_id' => 'required'
        ]);

        try {
            $stock = new StockAsset();
            $stock->asset_id = $request->asset_id;
            $stock->qty = $request->qty;
            $stock->vendor_id = $request->vendor_id;
            $stock->company = $company->unit_bisnis;
            $stock->save();

            return redirect()->route('asset-stocks.index')->with('success', 'Stock berhasil dibuat');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function UpdateStock(Request $request, $id)
    {
        $request->validate([
            'asset_id' => 'required',
            'qty' => 'required|integer',
            'vendor_id' => 'required'
        ]);

        try {
            $stock = StockAsset::findOrFail($id);
            $stock->asset_id = $request->asset_id;
            $stock->qty = $request->qty;
            $stock->vendor_id = $request->vendor_id;
            $stock->save();

            return redirect()->route('asset-stocks.index')->with('success', 'Stock berhasil diupdate');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function DestoryStock($id)
    {
        try {
            $stock = StockAsset::findOrFail($id);
            dd($stock);
            $stock->delete();
            return redirect()->route('asset-stocks.index')->with('success', 'Stock berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Stock Master End

    // Asset Category Start

    public function IndexCategory()
    {
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();

        $assetCategory = CategoryAsset::where('company', $company->unit_bisnis)->get();

        return view('pages.asset-management.category', compact('assetCategory'));
    }

    public function StoreCategory(Request $request)
    {
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();

        $request->validate([
            'name' => 'required',
        ]);

        try {
            $category = new CategoryAsset();
            $category->name = $request->name;
            $category->company = $company->unit_bisnis;
            $category->save();

            return redirect()->route('asset-category.index')->with('success', 'Category berhasil dibuat');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function UpdateCategory(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
        ]);

        try {
            $category = CategoryAsset::findOrFail($id);
            $category->name = $request->name;
            $category->save();

            return redirect()->route('asset-category.index')->with('success', 'Category berhasil diupdate');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function DestroyCategory($id)
    {
        try {
            $category = CategoryAsset::findOrFail($id);
            $category->delete();
            return redirect()->route('asset-category.index')->with('success', 'Category berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Asset Category End
}
