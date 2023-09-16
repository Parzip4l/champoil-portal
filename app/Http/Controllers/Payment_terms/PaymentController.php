<?php

namespace App\Http\Controllers\Payment_terms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Paymentterms;
use App\UserActivities;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $top = Paymentterms::all();
        return view('pages.accounting.payment-terms.index',compact('top'));
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
            'top' => 'required'
        ]);

        $uuid = Str::uuid()->toString();
        
        $at = new Paymentterms();
        $at->id = $uuid;
        $at->name = $request->input('name');
        $at->top = $request->input('top');
        $at->save();

        // User Activity
        $inputData = $request->all();
        $uuid2 = Str::uuid()->toString();

        $userActivity = new UserActivities();
        $userActivity->id = $uuid2;
        $userActivity->modul = 'TOP'; 
        $userActivity->id_item_modul = $at->id = $uuid;
        $userActivity->username = Auth::user()->name; 
        $userActivity->action = 'Created';
        $userActivity->old_values = null; 
        $userActivity->new_values = json_encode($inputData);

        $userActivity->save();

        return redirect()->route('terms-of-payment.index')->with('success', 'TOP Successfully Added');
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
            $at = Paymentterms::findOrFail($id);
            $oldValues = $at->toArray();
            $at->name = $request->name;
            $at->top = $request->top;
            $at->save();

            // user activities
            $uuid2 = Str::uuid()->toString();

            $userActivity = new UserActivities();
            $userActivity->id = $uuid2;
            $userActivity->modul = 'TOP';
            $userActivity->id_item_modul = $at->id; // Use the journal's ID
            $userActivity->username = Auth::user()->name;
            $userActivity->action = 'Updated';
            $userActivity->old_values = json_encode($oldValues);
            $userActivity->new_values = json_encode($at->toArray());

            $userActivity->save();

            return redirect()->route('terms-of-payment.index')->with('success', 'TOP Updated Successfully');
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
        $at = Paymentterms::find($id);
        $oldValues = $at->toArray();
        $at->delete();
        $uuid2 = Str::uuid()->toString();
        $userActivity = new UserActivities();
        $userActivity->id = $uuid2;
        $userActivity->modul = 'TOP'; 
        $userActivity->id_item_modul = $id;
        $userActivity->username = Auth::user()->name; 
        $userActivity->action = 'Deleted';
        $userActivity->old_values = json_encode($oldValues);
        $userActivity->new_values = null;

        $userActivity->save();
        return redirect()->route('terms-of-payment.index')->with('success', 'TOP Successfully Deleted');
    }
}
