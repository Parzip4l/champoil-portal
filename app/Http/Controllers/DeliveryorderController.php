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
                $tax = $detail['tax'];
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
        return view('pages.delivery.create', compact('billCode','Sales','customers','productDetails','existingDelivery'));
    }
}
