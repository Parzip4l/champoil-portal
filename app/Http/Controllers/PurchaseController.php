<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Purchase;
use App\Purchasedetails;
use App\Product;
use App\Productcategory;
use App\ContactM;
use App\PurchaseReceipt;
use App\WarehouseLoc;
use App\Tax;
use App\JournalItem;
use App\JournalEntry;
use App\AnalyticsAccount;
use App\ProductHistory;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $purchase = Purchase::leftJoin('contact', 'purchase.vendor', '=', 'contact.id')
        ->select('purchase.*', 'contact.name')
        ->orderBy('purchase.created_at', 'desc')
        ->get();
        return view('pages.purchase.index', compact('purchase'));
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
        $accountAnalytics = AnalyticsAccount::all();

        $currentYear = now()->year;
        $currentMonth = now()->format('m');

        // Temukan entri terbaru dengan kode yang sesuai
        $latestPurchase = Purchase::whereYear('created_at', $currentYear)
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
        $purchaseCode = "PO-IDGM-$currentYear-$currentMonth-$formattedCodeNumber";

        return view('pages.purchase.create',compact('contact','warehouse','product','product2','tax','tax2','purchaseCode','accountAnalytics'));
    }

    public function sendToSlack(Purchase $purchase)
    {
        $slackWebhookUrl = 'https://hooks.slack.com/services/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';


        $purchaseDetails = json_decode($purchase->data_product, true);
        foreach ($purchaseDetails as &$detail) {
            $product_id = $detail['product_id'];
            $unit_price = $detail['unit_price'];
            $quantity = $detail['quantity'];
            $tax = $detail['tax'];
            
            // Fetch product name using Eloquent (replace 'Product' with your actual model name)
            $product = Product::find($product_id);

            if ($product) {
                $detail['product_name'] = $product->name;
            } else {
                $detail['product_name'] = 'Product Not Found'; // Handle case when product is not found
            }
        }

        $data = [
            'text' => "Pembelian dengan kode: *{$purchase->code}* telah diterima!",
            'attachments' => [
                [
                    'title' => 'Detail Pembelian',
                    'fields' => [
                        [
                            'title' => 'Purchase Code',
                            'value' => $purchase->code,
                            'short' => true,
                        ],
                        [
                            'title' => 'Product Name',
                            'value' => $product->name,
                            'short' => true,
                        ],
                        [
                            'title' => 'Unit Price',
                            'value' => 'Rp. '.number_format($unit_price, 2),
                            'short' => true,
                        ],
                        [
                            'title' => 'Quantity',
                            'value' => $quantity,
                            'short' => true,
                        ],
                        [
                            'title' => 'Tax',
                            'value' => $tax,
                            'short' => true,
                        ],
                        [
                            'title' => 'Total',
                            'value' => 'Rp. '.number_format($purchase->total, 2),
                            'short' => true,
                        ],
                        [
                            'title' => 'Lihat Detail Data Di Champoil Portal',
                            'value' => '(http://127.0.0.1:8000/warehouse-stock)',
                            'short' => true,
                        ]
                    ],
                ],
            ],
            
        ];

        $data_string = json_encode($data);

        $ch = curl_init($slackWebhookUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string),
        ]);

        $result = curl_exec($ch);

        if ($result === false) {
            // Penanganan kesalahan jika Curl gagal
            $error = curl_error($ch);
            // Handle the error here
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengirim data ke Slack: ' . $error);
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($httpCode !== 200) {
            // Penanganan kesalahan jika Slack merespons selain status 200 OK
            // Handle the error here
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengirim data ke Slack. Kode status: ' . $httpCode);
        }

        curl_close($ch);

        // Handle the success response from Slack
        return redirect()->back()->with('success', 'Data berhasil dikirim ke Slack.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    // Validasi data yang dikirimkan oleh pengguna
        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'vendor' => 'required',
            'expected_arrival' => 'required|date',
            'warehouse' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Hitung total
            $total = 0;
            $purchaseDetails = [];
    
            for ($i = 0; $i < count($request->product); $i++) {
                $product_id = $request->product[$i];
                $unit_price = $request->unit_price[$i];
                $quantity = $request->quantity[$i];
                $category = $request->product_categories[$i];
                $tax = $request->tax[$i];
                $analytics = $request->analytics[$i];
                // Hitung subtotal
                $subtotal = ($unit_price * $quantity) * (1 + $tax);
                
    
                $purchaseDetails[] = [
                    'product_id' => $product_id,
                    'unit_price' => $unit_price,
                    'quantity' => $quantity,
                    'tax' => $tax,
                    'category' => $category,
                    'subtotal' => $subtotal,
                    'analytics' => $analytics,
                    'received_quantity' => 0, 
                    'remaining_quantity' => $quantity
                ];
    
                $total += $subtotal;
            }
    
            // Simpan data pembelian
            $uuid = Str::uuid()->toString();
            $purchase = new Purchase();
            $purchase->id = $uuid; // Generate UUID
            $purchase->code = $request->code;
            $purchase->vendor = $request->vendor;
            $purchase->expected_arrival = $request->expected_arrival;
            $purchase->warehouse = $request->warehouse;
            $purchase->status = $request->status;
            $purchase->total = $total; // Simpan total
            $purchase->data_product = json_encode($purchaseDetails); // Simpan details dalam bentuk JSON
            $purchase->save();
    
            return redirect()->route('purchase.index')->with('success', 'Purchase order telah berhasil disimpan.');
        } catch (\Exception $e) {
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

    public function receiveProductShow($id)
    {
        try {
            // Temukan pembelian berdasarkan ID
            $purchase = Purchase::find($id);
    
            if (!$purchase) {
                // Handle jika pembelian tidak ditemukan
                return redirect()->route('purchase.index')->with('error', 'Purchase order not found.');
            }
    
            // Decode data pembelian menjadi array
            $purchaseDetails = json_decode($purchase->data_product, true);
    
            // Inisialisasi array kosong untuk menyimpan informasi produk dan kode pembelian
            $productInfo = [];
    
            // Loop melalui setiap item dalam $purchaseDetails
            foreach ($purchaseDetails as $detail) {
                // Ambil nama produk berdasarkan id_product
                $product = Product::find($detail['product_id']);
                // Jika produk ditemukan, tambahkan informasi ke array
                if ($product) {
                    $productInfo[] = [
                        'product_name' => $product->product_name,
                        'purchase_code' => $purchase->purchase_code,
                    ];
                }
            }
    
            $purchaseReceipts = PurchaseReceipt::where('purchase_id', $id)->get();
    
            return view('pages.purchase.receive', compact('purchase', 'productInfo', 'purchaseReceipts','purchaseDetails'));
        } catch (\Exception $e) {
            // Tangani kesalahan yang mungkin terjadi
            return redirect()->route('purchase.index')->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
     

    public function partialReceive(Request $request, $id)
    {
        try {
            // Temukan pembelian berdasarkan ID
            $purchase = Purchase::find($id);
    
            if (!$purchase) {
                // Handle jika pembelian tidak ditemukan
                return redirect()->route('purchase.index')->with('error', 'Purchase order not found.');
            }

            $totalCredit = 0;
            $totalDebit = 0;
            $totalSubtotal = 0;
            $totalJournalAmount = 0;
            // Decode data pembelian menjadi array
            $purchaseDetails = json_decode($purchase->data_product, true);
            foreach ($purchaseDetails as $item) {
                $totalSubtotal += $item['subtotal'];
                $taxData = $item['tax'];
                $productNameData = $item['product_id'];
                $productCategories = $item['category'];
                $productAnalytics = $item['analytics'];
    
                $productCategory = Productcategory::where('id', $productCategories)->first();
                    
            }
    
            $totalReceivedQuantity = 0; // Total received quantity for all products
    
            foreach ($purchaseDetails as $key => $detail) {
                $product_id = $detail['product_id'];
                $received_quantity = $request->input('received_quantity.' . $product_id, 0);
    
                // Check if received quantity exceeds the remaining quantity
                if ($received_quantity > $detail['remaining_quantity']) {
                    return redirect()->route('purchase.index')->with('error', 'Received quantity exceeds remaining quantity for product ID: ' . $product_id);
                }
                
    
                $totalReceivedQuantity += $received_quantity;
                $totalJournalAmount += $received_quantity * $detail['unit_price'];

                if ($totalReceivedQuantity > 0) {
                    $vendorData = $purchase->vendor;
                    $AccountData = ContactM::where('id', $vendorData)->first();
                    $accountPay = $AccountData->account_pay;
                    $inputAccount = $productCategory->input_account;
                    $JournalAccount = $productCategory->journal;
                    // Generate UUID for credit entry
                    $uuidcredit = Str::uuid()->toString();
                    $creditEntry = new JournalItem();
                    $creditEntry->id = $uuidcredit;
                    $creditEntry->journal = $JournalAccount;
                    $creditEntry->journal_entry = $purchase->id;
                    $creditEntry->account = $accountPay;
                    $creditEntry->partner = $purchase->vendor;
                    $creditEntry->label = $purchase->code;
                    $creditEntry->debit = '0';
                    $creditEntry->credit = $totalJournalAmount; // Credit amount for this product
                    $creditEntry->tax = $taxData;
                    $creditEntry->product = $productNameData;
                    $creditEntry->analytics = $productAnalytics;
                    $creditEntry->balance = -$totalJournalAmount; // Balance for this entry
                    $creditEntry->save();

                    // Generate UUID for debit entry
                    $uuiddebit = Str::uuid()->toString();
                    $debitEntry = new JournalItem();
                    $debitEntry->id = $uuiddebit;
                    $debitEntry->journal = $JournalAccount;
                    $debitEntry->journal_entry = $purchase->id;
                    $debitEntry->account = $inputAccount;
                    $debitEntry->partner = $purchase->vendor;
                    $debitEntry->label = $purchase->code;
                    $debitEntry->debit = $totalJournalAmount; // Debit amount for this product
                    $debitEntry->credit = '0';
                    $debitEntry->tax = $taxData;
                    $debitEntry->product = $productNameData;
                    $debitEntry->analytics = $productAnalytics;
                    $debitEntry->balance = $totalJournalAmount; // Balance for this entry
                    $debitEntry->save();

                    // Code Entry
                    $currentYear = now()->year;
                    $currentMonth = now()->format('m');

                    // Temukan entri terbaru dengan kode yang sesuai
                    $latestEntry = JournalEntry::whereYear('created_at', $currentYear)
                        ->whereMonth('created_at', $currentMonth)
                        ->orderBy('created_at', 'desc')
                        ->first();

                    // Inisialisasi nomor urut
                    $nextCodeNumber = 1;

                    if ($latestEntry) {
                        // Jika ada entri terbaru, ambil nomor urut dari kode terbaru, tambahkan 1
                        $latestCode = $latestEntry->code;
                        $latestCodeParts = explode('-', $latestCode);
                        $nomorurut = end($latestCodeParts);
                        $nomorurut = ltrim($nomorurut, '0');
                        $lastCodeNumber = (int)$nomorurut;
                        $nextCodeNumber = $lastCodeNumber + 1;
                    }

                    // Format ulang nomor urut dengan panjang 5 digit
                    $formattedCodeNumber = str_pad($nextCodeNumber, 5, '0', STR_PAD_LEFT);

                    // Buat kode PO dengan format yang sesuai
                    $EntryCode = "STJ-$currentYear-$currentMonth-$formattedCodeNumber";

                    // Journal Entry
                    $uuidjournal = Str::uuid()->toString();
                    $journalentry = new JournalEntry();
                    $journalentry->id = $uuidjournal;
                    $journalentry->code = $EntryCode;
                    $journalentry->partner = $purchase->vendor;
                    $journalentry->reference = $purchase->id;
                    $journalentry->journal = $JournalAccount;
                    $journalentry->total = $totalJournalAmount;
                    $journalentry->status = 'Posted';
                    $journalentry->save();
                }
    
                // Update stok inventaris dan sisa kuantitas pada detail pembelian
                $inventory = Product::where('id', $product_id)->first();
                if ($inventory) {
                    $inventory->onhand += $received_quantity;
                    $inventory->save();

                    $idhistory = Str::uuid()->toString();
                    $inventoryHistory = new ProductHistory();
                    $inventoryHistory->id = $idhistory;
                    $inventoryHistory->product_id = $product_id;
                    $inventoryHistory->quantity = $received_quantity;
                    $inventoryHistory->modul = 'Purchase';
                    $inventoryHistory->action = 'in';
                    $inventoryHistory->save();
    
                    // Update sisa kuantitas pada detail pembelian
                    $DataSimpan = $purchaseDetails[$key]['remaining_quantity'] -= $received_quantity;
                    $purchase->data_product = json_encode($purchaseDetails);
                    // Simpan perubahan pada status pembelian
                    $purchase->save();

                    if ($DataSimpan === 0)
                    {
                        $purchase->status = 'Received';
                        $purchase->save();
                    }
                }
    
                // Simpan riwayat receipt
                $receipt = new PurchaseReceipt();
                $receipt->purchase_id = $purchase->id;
                $receipt->product_id = $product_id;
                $receipt->received_quantity = $received_quantity;
                $receipt->received_by = Auth::user()->name;
                $receipt->save();
            }
    
            // Tandai pembelian sebagai "Received" jika semua produk telah diterima
            if ($totalReceivedQuantity === array_sum(array_column($purchaseDetails, 'quantity'))) {
                $purchase->status = 'Received';
            } else {
                $purchase->status = 'Partially Received';
            }

            $sisaQuantitasProduk = array_column($purchaseDetails, 'remaining_quantity');
            if (array_sum($sisaQuantitasProduk) === 0) {
                $purchase->status = 'Received';
            } else {
                $purchase->status = 'Partially Received';
            }
    
            // Simpan perubahan pada status pembelian
            $purchase->save();
    
            if ($totalReceivedQuantity === array_sum(array_column($purchaseDetails, 'quantity'))) {
                // Full received
                return redirect()->back()->with('success', 'Purchase order has been received in full.');
            } else {
                // Partially received
                return redirect()->back()->with('success', 'Purchase order has been received partially.');
            }
        } catch (\Exception $e) {
            // Tangani kesalahan yang mungkin terjadi
            return redirect()->route('purchase.index')->with('error', 'An error occurred while partially receiving the purchase order: ' . $e->getMessage());
        }
    }


     public function receiveProduct($id)
     {
         try {
             // Temukan pembelian berdasarkan ID
             $purchase = Purchase::find($id);
     
             if (!$purchase) {
                 // Handle jika pembelian tidak ditemukan
                 return redirect()->route('purchase.index')->with('error', 'Purchase order not found.');
             }
     
             // Perbarui status pembelian menjadi "Received"
             $purchase->status = 'ReceiFved';
             $purchase->save();
     
             // Decode data pembelian menjadi array
             $purchaseDetails = json_decode($purchase->data_product, true);
     
             // Iterasi melalui purchaseDetails dan tambahkan stok ke inventaris
             foreach ($purchaseDetails as $detail) {
                 $product_id = $detail['product_id'];
                 $quantity = $detail['quantity'];
     
                 // Dapatkan data inventaris berdasarkan product_id
                $inventory = Product::where('id', $product_id)->first();
     
                 if ($inventory) {
                     // Jika inventaris ditemukan, tambahkan quantity yang dibeli ke stok saat ini
                    $inventory->onhand += $quantity;
                    $inventory->save();

                    $idhistory = Str::uuid()->toString();
                    $inventoryHistory = new ProductHistory();
                    $inventoryHistory->id = $idhistory;
                    $inventoryHistory->product_id = $product_id;
                    $inventoryHistory->quantity = $quantity;
                    $inventoryHistory->modul = 'Purchase';
                    $inventoryHistory->action = 'in';
                    $inventoryHistory->save();

                 } else {
                     // Jika inventaris tidak ditemukan, buat entri inventaris baru
                     $newInventory = new Inventory();
                     $newInventory->product_id = $product_id;
                     $newInventory->quantity = $quantity;
                     $newInventory->save();
                 }
             }
     
             return redirect()->route('purchase.index')->with('success', 'Purchase order has been received successfully.');
         } catch (\Exception $e) {
             // Tangani kesalahan yang mungkin terjadi
             return redirect()->route('purchase.index')->with('error', 'An error occurred while receiving the purchase order: ' . $e->getMessage());
         }
     }

    public function show($id)
    {
        $query = DB::table('purchase')
            ->join('contact', 'purchase.vendor', '=', 'contact.id')
            ->join('warehouse_loc', 'purchase.warehouse', '=', 'warehouse_loc.id')
            ->select(
                'purchase.*',
                'contact.name as contactname',
                'warehouse_loc.name as warehousename',
            )
            ->where('purchase.id', $id)
            ->first();

        if (!$query) {
            // Handle when the product with the given ID is not found
            abort(404);
        }

        $purchaseDetails = json_decode($query->data_product, true);

        // Iterate through purchaseDetails and fetch product names
        foreach ($purchaseDetails as &$detail) {
            $product_id = $detail['product_id'];
            
            // Fetch product name using Eloquent (replace 'Product' with your actual model name)
            $product = Product::find($product_id);

            if ($product) {
                $detail['product_name'] = $product->name;
            } else {
                $detail['product_name'] = 'Product Not Found'; // Handle case when product is not found
            }
        }
        
        return view('pages.purchase.details', ['purchase' => $query, 'purchaseDetails' => $purchaseDetails]);
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
