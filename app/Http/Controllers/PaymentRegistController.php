<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use App\PaymentRegist;
use App\VendorBill;

class PaymentRegistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(Request $request)
    {
        $request->validate([
            'journal' => 'required',
            'payment_date' => 'required'
        ]);

        $uuid = Str::uuid()->toString();
        
        $payment = new PaymentRegist();
        $payment->id = $uuid;
        $payment->payment_method = $request->input('payment_method');
        $payment->journal = $request->input('journal');
        $payment->recipient_bank_account = $request->input('recipient_bank_account');
        $payment->amount = $request->input('amount');   
        $payment->payment_date = $request->input('payment_date');
        $payment->memo = $request->input('memo');
        $payment->vendor_bill_id = $request->input('vendor_bill_id');

        $vendorBillId = $request->input('vendor_bill_id');
        VendorBill::where('id', $vendorBillId)->update(['payment_status' => 'Paid']);
        $payment->save();

        return redirect()->back()->with('success', 'Payment Successfully Added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
