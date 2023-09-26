<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Sales;
use App\ContactM;
use App\WarehouseLoc;
use App\Product;
use App\Tax;
use App\GrowthM;
use App\ProductHistory;
use App\AnalyticsAccount;
use App\Paymentterms;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sales = Sales::leftJoin('contact', 'sales.customer', '=', 'contact.id')
        ->select('sales.*', 'contact.name')
        ->orderBy('sales.code', 'desc')
        ->get();
        return view('pages.sales.index',compact('sales'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $contact = ContactM::all();
        $warehouse = WarehouseLoc::all();
        $product = Product::all();
        $product2 = Product::all();
        $tax = Tax::all();
        $tax2 = Tax::all();
        $salesteam = GrowthM::all();
        $top = Paymentterms::all();
        $accountAnalytics = AnalyticsAccount::all();

        $currentYear = now()->year;
        $currentMonth = now()->format('m');

        // Temukan entri terbaru dengan kode yang sesuai
        $latestPurchase = Sales::whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->orderBy('created_at', 'desc')
            ->first();

        // Inisialisasi nomor urut
        $nextCodeNumber = 1;

        if ($latestPurchase) {
            // Jika ada entri terbaru, ambil nomor urut dari kode terbaru, tambahkan 1
            $latestCode = $latestPurchase->code;
            $latestCodeParts = explode('-', $latestCode);
            $nomorurut = end($latestCodeParts);
            $nomorurut = ltrim($nomorurut, '0');
            $lastCodeNumber = (int)$nomorurut;
            $nextCodeNumber = $lastCodeNumber + 1;
        }

        // Format ulang nomor urut dengan panjang 5 digit
        $formattedCodeNumber = str_pad($nextCodeNumber, 5, '0', STR_PAD_LEFT);

        // Buat kode PO dengan format yang sesuai
        $purchaseCode = "SO-IDGM-$currentYear-$currentMonth-$formattedCodeNumber";

        // Tanggal Pembelian
        $orderdate = Carbon::now()->toDateString();
        return view('pages.sales.create',compact('purchaseCode','contact','warehouse','product','tax','product2','tax2','salesteam',
        'orderdate','top','accountAnalytics'
    ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'customer' => 'required',
            'order_date' => 'required|date',
            'delivery_date' => 'required',
            'sales_team' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {

            DB::beginTransaction();
            // Hitung total
            $total = 0;
            $sent_quantity = 0;
            $purchaseDetails = [];
    
            for ($i = 0; $i < count($request->product); $i++) {
                $product_id = $request->product[$i];
                $unit_price = $request->unit_price[$i];
                $quantity = $request->quantity[$i];
                $tax = $request->tax[$i];
                $category = $request->product_categories[$i];
                $remaining_quantity = $request->quantity[$i];
                $analytics = $request->analytics[$i];
                // Hitung subtotal
                $subtotal = ($unit_price * $quantity) * (1 + $tax);
                
    
                $purchaseDetails[] = [
                    'product_id' => $product_id,
                    'unit_price' => $unit_price,
                    'quantity' => $quantity,
                    'tax' => $tax,
                    'category' => $category,
                    'analytics' => $analytics,
                    'subtotal' => $subtotal,
                    'remaining_quantity' => $remaining_quantity,
                    'sent_quantity' => $sent_quantity,
                ];

                $product = Product::find($product_id);
                if (!$product) {
                    throw new \Exception("Produk dengan ID $product_id tidak ditemukan.");
                }
                $product->onhand -= $quantity;
                $product->save();
    
                $total += $subtotal;

                $idhistory = Str::uuid()->toString();
                $inventoryHistory = new ProductHistory();
                $inventoryHistory->id = $idhistory;
                $inventoryHistory->product_id = $product_id;
                $inventoryHistory->quantity = $quantity;
                $inventoryHistory->modul = 'Sales';
                $inventoryHistory->action = 'Out';
                $inventoryHistory->save();
            }
    
            // Simpan data pembelian
            $uuid = Str::uuid()->toString();
            $sales = new Sales();
            $sales->id = $uuid; // Generate UUID
            $sales->code = $request->code;
            $sales->customer = $request->customer;
            $sales->sales_team = $request->sales_team;
            $sales->order_date = $request->order_date;
            $sales->delivery_date = $request->delivery_date;
            $sales->ekspedition = $request->ekspedition;
            $sales->notes = $request->notes;
            $sales->payment_terms = $request->payment_terms;
            $sales->invoice_status = $request->invoice_status;
            $sales->delivery_status = $request->delivery_status;
            $sales->created_by = $request->created_by;
            $sales->total = $total; // Simpan total
            $sales->data_product = json_encode($purchaseDetails); // Simpan details dalam bentuk JSON
            $sales->save();
            
            DB::commit();
            return redirect()->route('sales.index')->with('success', 'Sales order telah berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollback();
            // Tangani kesalahan yang mungkin terjadi
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan pembelian: ' . $e->getMessage());
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
        $query = DB::table('sales')
            ->join('contact', 'sales.customer', '=', 'contact.id')
            ->select(
                'sales.*',
                'contact.name as contactname',
            )
            ->where('sales.id', $id)
            ->first();

        if (!$query) {
            // Handle when the product with the given ID is not found
            abort(404);
        }

        $salesDetails = json_decode($query->data_product, true);

        // Iterate through purchaseDetails and fetch product names
        foreach ($salesDetails as &$detail) {
            $product_id = $detail['product_id'];
            
            // Fetch product name using Eloquent (replace 'Product' with your actual model name)
            $product = Product::find($product_id);

            if ($product) {
                $detail['product_name'] = $product->name;
            } else {
                $detail['product_name'] = 'Product Not Found'; // Handle case when product is not found
            }
        }
        
        return view('pages.sales.details', ['sales' => $query, 'salesDetails' => $salesDetails]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
