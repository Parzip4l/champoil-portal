<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Uom;
use App\UomCategory;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class UomController extends Controller
{
    public function index()
    {
        $category = UomCategory::all();
        $querydata = DB::table('uom')
            ->join('uom_category', 'uom.uom_categories', '=', 'uom_category.id')
            ->select('uom.*', 'uom_category.name as kategoriname');
        
        $data = $querydata->get();
        return view ('pages.inventory.setting.uom.index', compact('data','category'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'uom_categories' => 'required'
        ]);

        $uuid = Str::uuid()->toString();
        
        $uom = new Uom();
        $uom->id = $uuid;
        $uom->name = $request->input('name');
        $uom->uom_categories = $request->input('uom_categories');
        $uom->save();

        return redirect()->route('uom.index')->with('success', 'UOM Successfully Added');
    }
}
