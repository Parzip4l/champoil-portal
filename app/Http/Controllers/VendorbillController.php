<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\VendorBill;
use App\Purchase;
use App\ContactM;
use App\Product;
use App\Productcategory;
use App\UserActivities;
use App\JournalItem;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class VendorbillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = DB::table('vendor_bills')
            ->join('purchase', 'vendor_bills.purchase_id', '=', 'purchase.id')
            ->select(
                'vendor_bills.*',
                'purchase.code as purchasecode',
                'purchase.total as total',
                'purchase.id as purchaseid'
            )
            ->get();

        if (!$query) {
            // Handle when the product with the given ID is not found
            abort(404);
        }

        $vendorbill = $query;
        return view ('pages.accounting.vendorbill.index',compact('vendorbill'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        $purchaseId = $request->input('purchase_id');
        $purchase = Purchase::find($purchaseId);

        $existingVendorBill = VendorBill::where('purchase_id', $purchaseId)->first();

        if ($purchase) {
            $vendor = ContactM::where('id', $purchase->vendor)->first();
            $vendorName = $vendor ? $vendor->vendorname : 'Vendor Not Found'; // Handle jika vendor tidak ditemukan
        } else {
            // Handle jika pembelian dengan purchase_id tertentu tidak ditemukan
            abort(404);
        }

        if ($purchase) {
            $purchaseDetails = json_decode($purchase->data_product, true);
        
            $productDetails = [];
            foreach ($purchaseDetails as $detail) {
                $productId = $detail['product_id'];
                $quantity = $detail['quantity'];
                $unit_price = $detail['unit_price'];
                $tax = $detail['tax'];
                $category = $detail['category'];
                $analytics = $detail['analytics'];
                $subtotal = $detail['subtotal'];

        
                // Cari data produk berdasarkan product_id
                $product = Product::find($productId);
        
                if ($product) {
                    $productDetails[] = [
                        'name' => $product->name,
                        'uom' => $product->purchase_uom, 
                        'quantity' => $quantity,
                        'tax' => $tax,
                        'unit_price' => $unit_price,
                        'category' => $category,
                        'analytics' => $analytics,
                        'subtotal' => $subtotal,
                    ];
                } else {
                    // Handle jika produk tidak ditemukan
                    $productDetails[] = [
                        'product_name' => 'Product Not Found',
                        'uom' => 'UOM Not Found',
                    ];
                }
            }
        
        } else {
            // Handle jika pembelian dengan purchase_id tertentu tidak ditemukan
            abort(404);
        }


        $currentYear = now()->year;
        $currentMonth = now()->format('m');

        // Temukan entri terbaru dengan kode yang sesuai
        $latestPurchase = VendorBill::whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->orderBy('created_at', 'desc')
            ->first();

        // Inisialisasi nomor urut
        $nextCodeNumber = 1;

        if ($latestPurchase) {
            // Jika ada entri terbaru, ambil nomor urut dari kode terbaru, tambahkan 1
            $latestCode = $latestPurchase->code;
            $latestCodeParts = explode('-', $latestCode);
            $lastCodeNumber = (int)end($latestCodeParts);
            $nextCodeNumber = $lastCodeNumber + 1;
        }

        // Format ulang nomor urut dengan panjang 5 digit
        $formattedCodeNumber = str_pad($nextCodeNumber, 5, '0', STR_PAD_LEFT);

        // Buat kode PO dengan format yang sesuai
        $billCode = "BILL-$currentYear-$currentMonth-$formattedCodeNumber";
        return view('pages.accounting.vendorbill.create', compact('billCode','purchase','vendor','productDetails','existingVendorBill'));
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
            'purchase_id' => 'required',
            'purchase_details' => 'required',
            'due_date' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Isi Due Date Terlebih Dahulu !');
        }

        try {
            DB::beginTransaction();
            // Simpan data pembelian
            $uuid = Str::uuid()->toString();
            $bills = new VendorBill();
            $bills->id = $uuid; // Generate UUID
            $bills->code = $request->code;
            $bills->vendor = $request->vendor;
            $bills->purchase_id = $request->purchase_id;
            $bills->purchase_details = $request->purchase_details;
            $bills->bill_date = $request->bill_date;
            $bills->accounting_date = $request->accounting_date;
            $bills->bank_receipt = $request->bank_receipt;
            $bills->due_date = $request->due_date;
            $bills->payment_status = $request->payment_status;
            $bills->status = $request->status;
            $bills->journal = $request->journal;
            $bills->created_by = $request->created_by;
            
            $bills->save();

            // Get Data Account Item Vendor 

            $inputData = $request->all();
            $uuid2 = Str::uuid()->toString();

            $userActivity = new UserActivities();
            $userActivity->id = $uuid2;
            $userActivity->modul = 'Vendor Bills'; 
            $userActivity->id_item_modul = $bills->id = $uuid;
            $userActivity->username = Auth::user()->name; 
            $userActivity->action = 'Created';
            $userActivity->old_values = null; 
            $userActivity->new_values = json_encode($inputData);

            $userActivity->save();

            $totalSubtotal = 0;

        $purchaseDetails = json_decode($request->purchase_details, true);
        foreach ($purchaseDetails as $item) {
            $totalSubtotal += $item['subtotal'];
            $taxData = $item['tax'];
            $productNameData = $item['name'];
            $productCategories = $item['category'];
            $productAnalytics = $item['analytics'];
        }

            DB::commit();
            return redirect()->route('vendor-bills.index')->with('success', 'Vendor Bills Succesfully Created.');
        } catch (\Exception $e) {
            DB::rollback();
            // Tangani kesalahan yang mungkin terjadi
            return redirect()->back()->with('error', 'Error When Created Vendor Bills.' . $e->getMessage());
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
        $query = DB::table('vendor_bills')
            ->join('purchase', 'vendor_bills.purchase_id', '=', 'purchase.id')
            ->select(
                'vendor_bills.*',
                'purchase.code as purchasecode',
                'purchase.total as total',
                'purchase.id as purchaseid'
            )
            ->where('vendor_bills.id', $id)
            ->first();

        if (!$query) {
            // Handle when the product with the given ID is not found
            abort(404);
        }

        $perPage = 5;
        $userActivities = UserActivities::where('modul', 'Vendor Bills') // Sesuaikan dengan modul yang sesuai
            ->where('id_item_modul', $id)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
        
        return view('pages.accounting.vendorbill.details', ['vendor_bills' => $query, 'userActivities' => $userActivities]);
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
