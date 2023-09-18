<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Productcategory;
use App\Product;
use App\Uom;
use App\WarehouseLoc;
use App\UserActivities;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $uom = Uom::all();
        $purchaseuom = Uom::all();
        $category = Productcategory::all();
        $warehouse = WarehouseLoc::all();
        $query = DB::table('product')
        ->join('product_category', 'product.category', '=', 'product_category.id')
        ->join('uom', 'product.uom', '=', 'uom.id')
        ->join('warehouse_loc', 'product.warehouse', '=', 'warehouse_loc.id')
        ->select(
            'product.*',
            'product_category.name as categoryname',
            'uom.name as uom_name',
            'warehouse_loc.name as warehouse_name'
        );

        $data = $query->get();
        return view('pages.inventory.index',compact('data','uom','category','purchaseuom','warehouse'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function getLastProductCode(Request $request)
    {
        $category = $request->input('category');
        $lastProduct = Product::where('category', $category)
            ->orderBy('created_at', 'desc')
            ->first();
    
        $lastCode = ($lastProduct) ? $lastProduct->code : '000';
    
        return response()->json(['lastCode' => $lastCode]);
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required',
            'name' => 'required'
        ]);

        $uuid = Str::uuid()->toString();
        
        $product = new Product();
        $product->id = $uuid;
        $product->code = $request->input('code');
        $product->name = $request->input('name');
        $product->type = $request->input('type');
        $product->uom = $request->input('uom');
        $product->purchase_uom = $request->input('purchase_uom');
        $product->taxes = $request->input('taxes');
        $product->cost = $request->input('cost');
        $product->category = $request->input('category');
        $product->onhand = $request->input('onhand');
        $product->warehouse = $request->input('warehouse');
        $product->save();

        $inputData = $request->all();
        $uuid2 = Str::uuid()->toString();

        $userActivity = new UserActivities();
        $userActivity->id = $uuid2;
        $userActivity->modul = 'Product'; 
        $userActivity->id_item_modul = $product->id = $uuid;
        $userActivity->username = Auth::user()->name; 
        $userActivity->action = 'Created';
        $userActivity->old_values = null; 
        $userActivity->new_values = json_encode($inputData);

        $userActivity->save();

        return redirect()->route('inventory-product.index')->with('success', 'Inventory Successfully Added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $query = DB::table('product')
            ->join('product_category', 'product.category', '=', 'product_category.id')
            ->join('uom', 'product.uom', '=', 'uom.id')
            ->join('warehouse_loc', 'product.warehouse', '=', 'warehouse_loc.id')
            ->select(
                'product.*',
                'product_category.name as categoryname',
                'uom.name as uom_name',
                'warehouse_loc.name as warehouse_name'
            )
            ->where('product.id', $id)
            ->first();

        if (!$query) {
            // Handle when the product with the given ID is not found
            abort(404);
        }

        $perPage = 5;
        $historyQuery = DB::table('inventory_histories')
            ->where('product_id', $id)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        $userActivities = UserActivities::where('modul', 'Product') // Sesuaikan dengan modul yang sesuai
            ->where('id_item_modul', $id)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
        
        return view('pages.inventory.details', ['product' => $query, 'history' => $historyQuery, 'userActivities' => $userActivities]);
    }

    public function getProductsByCategory($category_id)
    {
        $uom = Uom::all();
        $purchaseuom = Uom::all();
        $category = Productcategory::all();
        $warehouse = Warehouseloc::all();
        $data = DB::table('product')
            ->join('product_category', 'product.category', '=', 'product_category.id')
            ->join('uom', 'product.uom', '=', 'uom.id')
            ->join('warehouse_loc', 'product.warehouse', '=', 'warehouse_loc.id')
            ->select(
                'product.*',
                'product_category.name as categoryname',
                'uom.name as uom_name',
                'warehouse_loc.name as warehouse_name'
            )
            ->where('product.category', '=', $category_id)
            ->get();
        // Tampilkan produk yang sesuai dengan kategori di halaman
        return view('pages.inventory.bycategory', compact('data','purchaseuom','category','warehouse','uom'));
    }

    public function getProductsByWarehouse($warehouse_id)
    {
        $uom = Uom::all();
        $purchaseuom = Uom::all();
        $category = Productcategory::all();
        $warehouse = Warehouseloc::all();
        $data = DB::table('product')
            ->join('product_category', 'product.category', '=', 'product_category.id')
            ->join('uom', 'product.uom', '=', 'uom.id')
            ->join('warehouse_loc', 'product.warehouse', '=', 'warehouse_loc.id')
            ->select(
                'product.*',
                'product_category.name as categoryname',
                'uom.name as uom_name',
                'warehouse_loc.name as warehouse_name'
            )
            ->where('product.warehouse', '=', $warehouse_id)
            ->get();
        // Tampilkan produk yang sesuai dengan warehouse di halaman
        return view('pages.inventory.bywarehouse', compact('data','purchaseuom','category','warehouse','uom'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $query = DB::table('product')
            ->join('product_category', 'product.category', '=', 'product_category.id')
            ->join('uom', 'product.uom', '=', 'uom.id')
            ->join('warehouse_loc', 'product.warehouse', '=', 'warehouse_loc.id')
            ->select(
                'product.*',
                'product_category.name as categoryname',
                'uom.name as uom_name',
                'warehouse_loc.name as warehouse_name'
            )
            ->where('product.id', $id)
            ->first();

        if (!$query) {
            // Handle when the product with the given ID is not found
            abort(404);
        }

        $uom = Uom::all();
        $purchaseuom = Uom::all();
        $warehouse = Warehouseloc::all();
        $category = Productcategory::all();
        return view('pages.inventory.edit', ['product' => $query, 'uom' => $uom, 'warehouse' => $warehouse, 'category' => $category]);
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
            $product = Product::findOrFail($id);
            $product->name = $request->name;
            $product->type = $request->type;
            $product->uom = $request->uom;
            $product->purchase_uom = $request->purchase_uom;
            $product->taxes = $request->taxes;
            $product->cost = $request->cost;
            $product->category = $request->category;
            $product->warehouse = $request->warehouse;
            $product->save();
            return redirect()->route('inventory-product.index')->with('success', 'Product Updated Successfully');
        } catch (ModelNotFoundException $e) {
            // Handle the case where the record with the specified $id is not found
            return redirect()->back()->withErrors('Data not found.');
        } catch (ValidationException $e) {
            // Handle the case where validation fails
            return redirect()->back()->withErrors($e->errors());
        } catch (\Exception $e) {
            // Handle other unexpected errors
            return redirect()->back()->withErrors('An error occurred while updating the data.');
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
        $product = Product::find($id);
        $product->delete();
        return redirect()->route('inventory-product.index')->with('success', 'Product Successfully Deleted');
    }
}
