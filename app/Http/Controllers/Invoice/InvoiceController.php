<?php

namespace App\Http\Controllers\Invoice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Invoice;
use App\Sales;
use App\Product;
use App\Journal;
use App\ContactM;
use App\AnalyticsAccount;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoice = Invoice::all();
        return view('pages.accounting.invoice.index', compact('invoice'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $SalesID = $request->input('sales_id');
        $SalesData = Sales::findOrFail($SalesID);

        $journal = Journal::where('type','Sales')->first();
        $ExistingCode = Invoice::where('so_code', $SalesID)->get();

        if ($SalesData) {
            $vendor = ContactM::where('id', $SalesData->customer)->first();
            $vendorName = $vendor ? $vendor->vendorname : 'Vendor Not Found'; // Handle jika vendor tidak ditemukan
        } else {
            // Handle jika pembelian dengan purchase_id tertentu tidak ditemukan
            abort(404);
        }

        if ($SalesData) {
            $SalesDetails = json_decode($SalesData->data_product, true);
        
            $productDetails = [];
            foreach ($SalesDetails as $detail) {
                $productId = $detail['product_id'];
                $quantity = $detail['quantity'];
                $unit_price = $detail['unit_price'];
                $tax = $detail['tax'];
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
        $latestInvoice = Invoice::whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->orderBy('created_at', 'desc')
            ->first();

        // Inisialisasi nomor urut
        $nextCodeNumber = 1;

        if ($latestInvoice) {
            // Jika ada entri terbaru, ambil nomor urut dari kode terbaru, tambahkan 1
            $latestCode = $latestInvoice->code;
            $latestCodeParts = explode('-', $latestCode);
            $lastCodeNumber = (int)end($latestCodeParts);
            $nextCodeNumber = $lastCodeNumber + 1;
        }

        // Format ulang nomor urut dengan panjang 5 digit
        $formattedCodeNumber = str_pad($nextCodeNumber, 5, '0', STR_PAD_LEFT);

        // Buat kode PO dengan format yang sesuai
        $InvoiceCode = "INV-$currentYear-$currentMonth-$formattedCodeNumber";
        return view('pages.accounting.invoice.create', compact('InvoiceCode','productDetails','SalesData','vendor','ExistingCode','journal'));
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
            'customer' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {

            DB::beginTransaction();
    
            // Simpan data Invoice
            $uuid = Str::uuid()->toString();
            $invoice = new Invoice();
            $invoice->id = $uuid; // Generate UUID
            $invoice->code = $request->code;
            $invoice->customer = $request->customer;
            $invoice->type = 'Regular';
            $invoice->product_data = $request->product_data;
            $invoice->invoice_date = $request->invoice_date;
            $invoice->due_date = $request->due_date;
            $invoice->total = $request->total;
            $invoice->payment_status = 'Not Paid';
            $invoice->invoice_status = 'Posted';
            $invoice->sales_team = $request->sales_team;
            $invoice->created_by = Auth::user()->name;
            $invoice->so_code = $request->so_code;
            $invoice->journal = $request->journal;
            $invoice->save();
            
            DB::commit();
            return redirect()->route('invoice.index')->with('success', 'Inovice Succesfully Created.');
        } catch (\Exception $e) {
            DB::rollback();
            // Tangani kesalahan yang mungkin terjadi
            return redirect()->back()->with('error', 'Error When Created Invoice : ' . $e->getMessage());
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
        $invoice = Invoice::findOrFail($id);

        $datainvoice = Invoice::where('id', $id)->get();
        return view('pages.accounting.invoice.details', compact('datainvoice'));
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
