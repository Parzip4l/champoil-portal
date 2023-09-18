<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Productcategory;
use App\CoaM;
use App\Journal;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category = Productcategory::all();
        $at = CoaM::all();
        $journal = Journal::all();
        return view ('pages.inventory.category.index', compact('category','at','journal'));
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
            'name' => 'required'
        ]);

        $uuid = Str::uuid()->toString();
        
        $category = new Productcategory();
        $category->id = $uuid;
        $category->name = $request->input('name');
        $category->short_code = $request->input('short_code');
        $category->parent_categories = $request->input('parent_categories');
        $category->income_account = $request->input('income_account');
        $category->expanse_account = $request->input('expanse_account');
        $category->journal = $request->input('journal');
        $category->valuation_account = $request->input('valuation_account');
        $category->input_account = $request->input('input_account');
        $category->output_account = $request->input('output_account');
        $category->save();

        return redirect()->route('product-category.index')->with('success', 'Category Successfully Added');
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
            $category = Productcategory::findOrFail($id);
            $category->name = $request->name;
            $category->short_code = $request->short_code;
            $category->income_account = $request->income_account;
            $category->expanse_account = $request->expanse_account;
            $category->journal = $request->journal;
            $category->valuation_account = $request->valuation_account;
            $category->input_account = $request->input_account;
            $category->output_account = $request->output_account;
            $category->save();
            return redirect()->route('product-category.index')->with('success', 'Product Category Updated Successfully');
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
        $category = Productcategory::find($id);
        $category->delete();
        return redirect()->route('product-category.index')->with('success', 'Product Category Successfully Deleted');
    }
}
