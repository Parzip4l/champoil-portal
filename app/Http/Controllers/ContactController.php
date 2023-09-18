<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ContactM;
use App\CoaM;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contact = ContactM::all();
        $coa = CoaM::all();
        return view ('pages.contact.index', compact('contact','coa'));
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
            'address' => 'required',
            'categories' => 'required'
        ]);

        $uuid = Str::uuid()->toString();
        
        $contact = new ContactM();
        $contact->id = $uuid;
        $contact->name = $request->input('name');
        $contact->categories = $request->input('categories');
        $contact->address = $request->input('address');
        $contact->city = $request->input('city');
        $contact->state = $request->input('state');
        $contact->email = $request->input('email');
        $contact->phone = $request->input('phone');
        $contact->mobile = $request->input('mobile');
        $contact->website = $request->input('website');
        $contact->tags = $request->input('tags');
        $contact->bank = $request->input('bank');
        $contact->bank_name = $request->input('bank_name');
        $contact->bank_number = $request->input('bank_number');
        $contact->account_pay = $request->input('account_pay');
        $contact->account_receive = $request->input('account_receive');
        $contact->save();

        return redirect()->route('contact.index')->with('success', 'Contact Successfully Added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $contact = ContactM::find($id);

        $previousContact = ContactM::where('id', '<', $id)->orderBy('id', 'desc')->first();
        $nextContact = ContactM::where('id', '>', $id)->orderBy('id')->first();

        if (!$contact) {
            return abort(404);
        }

        return view('pages.contact.details', compact('contact','previousContact','nextContact'));
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
            $contact = ContactM::findOrFail($id);
            $contact->name = $request->name;
            $contact->categories = $request->categories;
            $contact->mobile = $request->mobile;
            $contact->phone = $request->phone;
            $contact->address = $request->address;
            $contact->city = $request->city;
            $contact->state = $request->state;
            $contact->email = $request->email;
            $contact->website = $request->website;
            $contact->tags = $request->tags;
            $contact->save();
            return redirect()->route('contact.index')->with('success', 'Contact Updated Successfully');
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
        $contact = ContactM::find($id);
        $contact->delete();
        return redirect()->route('contact.index')->with('success', 'Contact Successfully Deleted');
    }
}
