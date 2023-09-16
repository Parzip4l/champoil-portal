<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Journal;
use App\CoaM;
use App\UserActivities;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

class JournalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $coa = CoaM::all();
       $useractivities = UserActivities::where('modul', 'Journal')
            ->orderBy('created_at', 'desc')
            ->get();
       $journal = Journal::leftJoin('coa', 'journal.default_account', '=', 'coa.id')
            ->select('journal.*', 'coa.name as coa_name')
            ->get();
       return view('pages.accounting.journal.index',compact('journal','coa','useractivities'));
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
            'short_code' => 'required',
            'type' => 'required'
        ]);

        $uuid = Str::uuid()->toString();
        
        $journal = new Journal();
        $journal->id = $uuid;
        $journal->name = $request->input('name');
        $journal->type = $request->input('type');
        $journal->short_code = $request->input('short_code');
        $journal->default_account = $request->input('default_account');
        $journal->save();

        $inputData = $request->all();
        $uuid2 = Str::uuid()->toString();

        $userActivity = new UserActivities();
        $userActivity->id = $uuid2;
        $userActivity->modul = 'Journal'; 
        $userActivity->id_item_modul = $journal->id = $uuid;
        $userActivity->username = Auth::user()->name; 
        $userActivity->action = 'Created';
        $userActivity->old_values = null; 
        $userActivity->new_values = json_encode($inputData);

        $userActivity->save();

        return redirect()->route('journal.index')->with('success', 'Journal Successfully Added');
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
            // Find the journal record before updating it
            $journal = Journal::findOrFail($id);

            // Capture the old_values before updating
            $oldValues = $journal->toArray();

            // Update the journal record with the new values
            $journal->name = $request->name;
            $journal->short_code = $request->short_code;
            $journal->type = $request->type;
            $journal->default_account = $request->default_account;
            $journal->save();

            // Create a new UserActivities record with old_values and new_values
            $uuid2 = Str::uuid()->toString();
            $userActivity = new UserActivities();
            $userActivity->id = $uuid2;
            $userActivity->modul = 'Journal';
            $userActivity->id_item_modul = $journal->id; // Use the journal's ID
            $userActivity->username = Auth::user()->name;
            $userActivity->action = 'Updated';
            $userActivity->old_values = json_encode($oldValues);
            $userActivity->new_values = json_encode($journal->toArray());

            $userActivity->save();

            return redirect()->route('journal.index')->with('success', 'Journal Updated Successfully');
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
        $journal = Journal::find($id);
        $oldValues = $journal->toArray();
        $journal->delete();
        $uuid2 = Str::uuid()->toString();
        $userActivity = new UserActivities();
        $userActivity->id = $uuid2;
        $userActivity->modul = 'Journal'; 
        $userActivity->id_item_modul = $id;
        $userActivity->username = Auth::user()->name; 
        $userActivity->action = 'Deleted';
        $userActivity->old_values = json_encode($oldValues);
        $userActivity->new_values = null;

        $userActivity->save();
        return redirect()->route('journal.index')->with('success', 'Journal Successfully Deleted');
    }
}
