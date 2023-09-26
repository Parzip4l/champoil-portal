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
use App\Deliverysales;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class DeliveryorderController extends Controller
{
    public function index()
    {
        $delivery = Deliverysales::all();
        return view ('pages.delivery.index', compact('delivery'));
    }

    public function create(Request $request)
    {
        $salesId = $request->input('sales_id');
        $Sales = Sales::find($salesId);

        $existingDelivery = Deliverysales::where('so_id', $salesId)->first();
        $HistoryDelivery = Deliverysales::where('so_id', $salesId)->orderBy('created_at', 'desc')->get();
        if ($Sales) {
            $customers = ContactM::where('id', $Sales->customer_id)->first();
            $customersname = $customers ? $customers->name : 'Vendor Not Found'; // Handle jika vendor tidak ditemukan
        } else {
            // Handle jika pembelian dengan purchase_id tertentu tidak ditemukan
            abort(404);
        }

        if ($Sales) {
            $salesDetails = json_decode($Sales->data_product, true);
        
            $productDetails = [];
            foreach ($salesDetails as $detail) {
                $productId = $detail['product_id'];
                $quantity = $detail['quantity'];
                $unit_price = $detail['unit_price'];
                $category = $detail['category'];
                $analytics = $detail['analytics'];
                $tax = $detail['tax'];
                $subtotal = $detail['subtotal'];
                $remaining_quantity = $detail['remaining_quantity'];
                $sent_quantity = $detail['sent_quantity'];

        
                // Cari data produk berdasarkan product_id
                $product = Product::find($productId);
        
                if ($product) {
                    $productDetails[] = [
                        'product_id' => $product->id,
                        'name' => $product->name,
                        'uom' => $product->purchase_uom, 
                        'quantity' => $quantity,
                        'tax' => $tax,
                        'category' => $category,
                        'analytics' => $analytics,
                        'unit_price' => $unit_price,
                        'subtotal' => $subtotal,
                        'remaining_quantity' => $remaining_quantity,
                        'sent_quantity' => $sent_quantity,
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
        $latestPurchase = Deliverysales::whereYear('created_at', $currentYear)
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
        $billCode = "FNG-OUT-$currentYear-$currentMonth-$formattedCodeNumber";
        return view('pages.delivery.create', compact('billCode','Sales','customers','productDetails','existingDelivery','HistoryDelivery'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
        
            // Ambil data yang dikirim dari form
            $code = $request->input('code');
            $so_id = $request->input('so_id');
            $customer_id = $request->input('customer_id');
            $delivery_address = $request->input('delivery_address');
            $expedition = $request->input('expedition');
            $sales_team = $request->input('sales_team');
            $order_details = json_decode($request->input('order_details'), true);
            $qtysend = $request->input('qtysend');
        
            // Temukan penjualan berdasarkan ID
            $sales = Sales::find($so_id);
        
            if (!$sales) {
                return redirect()->route('vendor-bills.index')->with('error', 'Sales order not found.');
            }
        
            // Menggabungkan quantity yang dikirim dengan detail pesanan
            $updatedOrderDetails = [];
            foreach ($order_details as $index => $detail) {
                $quantitySent = isset($qtysend[$index]) ? (int) $qtysend[$index] : 0;
        
                // Mengurangi sisa quantity yang perlu dikirim
                $remainingQuantity = max(0, $detail['remaining_quantity'] - $quantitySent);
                
                // Menambahkan quantity yang sudah dikirim
                $detail['sent_quantity'] = $quantitySent;
        
                $updatedOrderDetails[] = $detail;
        
                // Update remaining quantity in the original order details
                $order_details[$index]['remaining_quantity'] = $remainingQuantity;
                $order_details[$index]['sent_quantity'] = $quantitySent;
            }
        
            // Simpan pesanan pengiriman
            $uuid = Str::uuid()->toString();
            $deliveryOrder = new Deliverysales();
            $deliveryOrder->id = $uuid;
            $deliveryOrder->code = $code;
            $deliveryOrder->customer_id = $customer_id;
            $deliveryOrder->delivery_address = $delivery_address;
            $deliveryOrder->expedition = $expedition;
            $deliveryOrder->sales_team = $sales_team;
            $deliveryOrder->so_id = $so_id;
            $deliveryOrder->so_code = $sales->code;
            $deliveryOrder->order_details = json_encode($order_details);
        
            // Simpan pesanan pengiriman
            $deliveryOrder->save();
            // Update data_product di penjualan
            $sales->data_product = json_encode($order_details);
            $sales->save();
        
            // Update status penjualan menjadi "Full Delivery" jika semua barang dikirim
            $remainingQuantityTotal = array_sum(array_column($order_details, 'remaining_quantity'));
            if ($remainingQuantityTotal === 0) {
                $sales->delivery_status = 'Full Delivery';
                $sales->save();
            }
        
            // Kurangi stok produk berdasarkan quantity yang dikirim
            foreach ($updatedOrderDetails as $detail) {
                $productId = $detail['product_id'];
                $quantitySent = isset($qtysend[$index]) ? (int) $qtysend[$index] : 0;
                $product = Product::find($productId);
                if ($product) {
                    // Mengurangi stok inventaris
                    $product->onhand -= $quantitySent;
                    $product->save();
        
                    $uuidhistory = Str::uuid()->toString();
                    // Menyimpan riwayat perubahan stok
                    $productHistory = new ProductHistory();
                    $productHistory->id = $uuidhistory;
                    $productHistory->product_id = $productId;
                    $productHistory->quantity = -$quantitySent;
                    $productHistory->modul = 'Delivery';
                    $productHistory->action = 'out';
                    $productHistory->save();
                }
            }
        
            DB::commit();
            return redirect()->back()->with('success', 'Delivery order has been created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred while creating the delivery order: ' . $e->getMessage());
        }
              
    }
}
