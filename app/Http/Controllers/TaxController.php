<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tax;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaxController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tax = Tax::all();
        return view('pages.accounting.tax.index', compact('tax'));
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
            'name' => 'required',
            'tax' => 'required'
        ]);

        $uuid = Str::uuid()->toString();
        
        $tax = new Tax();
        $tax->id = $uuid;
        $tax->name = $request->input('name');
        $tax->tax = $request->input('tax');
        $tax->save();

        return redirect()->route('tax.index')->with('success', 'Tax Successfully Added');
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
        try {
            $tax = Tax::findOrFail($id);
            $tax->name = $request->name;
            $tax->tax = $request->tax;
            $tax->save();
            return redirect()->route('tax.index')->with('success', 'Tax Updated Successfully');
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
        $tax = Tax::find($id);
        $tax->delete();
        return redirect()->route('tax.index')->with('success', 'Tax Successfully Deleted');
    }
}
