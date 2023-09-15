<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Accounttype;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccountTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $at = Accounttype::all();
        return view ('pages.accounting.coa.account-type.index',compact('at'));
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
            'category' => 'required'
        ]);

        $uuid = Str::uuid()->toString();
        
        $at = new Accounttype();
        $at->id = $uuid;
        $at->name = $request->input('name');
        $at->category = $request->input('category');
        $at->save();

        return redirect()->route('account-type.index')->with('success', 'Account Type Successfully Added');
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
            $at = Accounttype::findOrFail($id);
            $at->name = $request->name;
            $at->category = $request->category;
            $at->save();
            return redirect()->route('account-type.index')->with('success', 'Account Type Updated Successfully');
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
        $at = Accounttype::find($id);
        if (!$at) {
            return redirect()->route('account-type.index')->with('error', 'Account Type not found');
        }

        $at->delete();
        return redirect()->route('account-type.index')->with('success', 'Account Type Successfully Deleted');
    }
}
