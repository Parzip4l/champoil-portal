<?php

namespace App\Http\Controllers\Journal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\JournalEntry;
use App\Purchase;
use App\CoaM;
use App\ContactM;
use App\Journal;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class JournalEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $journal = DB::table('journal_entry')
            ->join('contact', 'journal_entry.partner', '=', 'contact.id')
            ->join('purchase', 'journal_entry.reference', '=', 'purchase.id')
            ->join('journal', 'journal_entry.journal', '=', 'journal.id')
            ->select('journal_entry.*', 'contact.name as partnername', 'purchase.code as purchasecode', 'journal.name as journalname')
            ->get();

        if (!$journal) {
            // Handle when the product with the given ID is not found
            abort(404);
        }
        return view('pages.accounting.journal.journal-entry',compact('journal'));
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
        //
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
