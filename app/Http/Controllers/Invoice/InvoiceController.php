<?php

namespace App\Http\Controllers\Invoice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Invoice\InvoiceModel;
use App\Employee;
use PDF;
use Yajra\DataTables\Facades\DataTables;
use App\KasManagement\CustomerManagement;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $code = Auth::user()->employee_code;
            $company = Employee::where('nik', $code)->first();

            $invoices = InvoiceModel::where('company', $company->unit_bisnis)
                ->orderBy('created_at', 'desc')
                ->get();

            return DataTables::of($invoices)
                ->addIndexColumn()
                ->addColumn('status', function($row) {
                    return $row->status == 0 ? 'Belum Lunas' : 'Lunas';
                })
                ->addColumn('actions', function($row) {
                    return view('partials.actions', compact('row'))->render();
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('pages.invoice.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();
        $customer = CustomerManagement::where('company',$company->unit_bisnis)->get();

        return view('pages.invoice.create', compact('customer'));
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
            'date' => 'required',
        ]);

        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();
        
        try {
            // Generate the invoice code
            $randomNumber = rand(1000, 9999);
            $currentDate = date('d-m-Y');
            $invoiceCode = "INV-{$currentDate}-{$randomNumber}";

            // Check if the generated code is unique, if not, regenerate
            while (InvoiceModel::where('code', $invoiceCode)->exists()) {
                $randomNumber = rand(1000, 9999);
                $invoiceCode = "INV-{$currentDate}-{$randomNumber}";
            }

            // Calculate the total
            $total = array_sum(array_column($request->items, 'subtotal'));

            // Add the total to the details array
            $details = $request->items;
            $details['total'] = $total;

            // Create and save the invoice
            $invoice = new InvoiceModel();
            $invoice->code = $invoiceCode;
            $invoice->date = $request->date;
            $invoice->due_date = $request->due_date;
            $invoice->client = $request->client;
            $invoice->status = 0;
            $invoice->details = json_encode($details); 
            $invoice->created_by = $code;
            $invoice->company = $company->unit_bisnis; 
            $invoice->save();

            return redirect()->route('invoice.index')->with('success', 'Invoice created successfully.');
        } catch (\Exception $e) {
            \Log::error('Error creating invoice: '.$e->getMessage());

            return redirect()->back()->with('error', 'There was an error creating the invoice. Please try again.'.$e->getMessage());
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
        $invoice = InvoiceModel::findOrFail($id);

        // Decode details JSON into a more usable format
        $details = json_decode($invoice->details, true);
        $total = array_sum(array_column($details, 'subtotal'));

        return view('pages.invoice.show', compact('invoice','details','total'));
    }

    public function print($id)
{
    // Fetch the invoice and its details
    $invoice = InvoiceModel::findOrFail($id);
    $details = json_decode($invoice->details, true); // Decode as an associative array

    // Calculate totals
    $total = array_sum(array_column($details, 'subtotal')); // Calculate total from details
    $paymentMade = $invoice->payment_made; // Assuming this is stored separately
    $balanceDue = $total - $paymentMade;

    // Load the view and pass the data to it
    $pdf = PDF::loadView('pages.invoice.print', [
        'invoice' => $invoice,
        'details' => $details,
        'total' => $total,
        'paymentMade' => $paymentMade,
        'balanceDue' => $balanceDue
    ]);

    // Set paper size to A4 and orientation to landscape
    $pdf->setPaper('A4', 'landscape');

    // Return the PDF as a download response
    return $pdf->stream('invoice-' . $invoice->code . '.pdf');
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
