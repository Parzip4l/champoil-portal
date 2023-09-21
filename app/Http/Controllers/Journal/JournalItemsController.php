<?php

namespace App\Http\Controllers\Journal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\JournalItem;
use App\CoaM;
use App\ContactM;
use App\Journal;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class JournalItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $journal = DB::table('journal_item')
            ->join('contact', 'journal_item.partner', '=', 'contact.id')
            ->join('coa', 'journal_item.account', '=', 'coa.id')
            ->join('analytics_account', 'journal_item.analytics', '=', 'analytics_account.id')
            ->select('journal_item.*', 'contact.name as partnername', 'coa.name as accountname', 'analytics_account.name as analyticsname')
            ->get();

        if (!$journal) {
            // Handle when the product with the given ID is not found
            abort(404);
        }
        return view('pages.accounting.journal.journal-item',compact('journal'));
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
