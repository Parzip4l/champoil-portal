<?php

namespace App\Http\Controllers\Analytics;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\AnalyticsAccount;
use App\AnalyticsPlans;
use App\UserActivities;
use App\JournalItem;
use App\Purchase;
use App\VendorBill;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;


class AnalyticsAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = DB::table('analytics_account')
            ->join('analytics_plans', 'analytics_account.plan', '=', 'analytics_plans.id')
            ->select('analytics_account.*', 'analytics_plans.name as plansname');
        $data = $query->get();

        $plan = AnalyticsPlans::all();

        return view('pages.accounting.analytics.account',compact('data','plan'));
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
            'code' => 'required'
        ]);

        $uuid = Str::uuid()->toString();
        
        $plans = new AnalyticsAccount();
        $plans->id = $uuid;
        $plans->name = $request->input('name');
        $plans->plan = $request->input('plan');
        $plans->code = $request->input('code');
        $plans->save();

        $inputData = $request->all();
        $uuid2 = Str::uuid()->toString();

        $userActivity = new UserActivities();
        $userActivity->id = $uuid2;
        $userActivity->modul = 'Analytics Account'; 
        $userActivity->id_item_modul = $plans->id = $uuid;
        $userActivity->username = Auth::user()->name; 
        $userActivity->action = 'Created';
        $userActivity->old_values = null; 
        $userActivity->new_values = json_encode($inputData);

        $userActivity->save();
        
        return redirect()->route('analytics-account.index')->with('success', 'Analytics Account Successfully Added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $account = AnalyticsAccount::find($id);

        $dataaccount = AnalyticsAccount::where('id', $id)->first();
        if ($account) {
            $vendorBills = VendorBill::whereJsonContains('purchase_details', ['analytics' => $id])->get();
            $Purchase = Purchase::whereJsonContains('data_product', ['analytics' => $id])->get();
        } else {
            abort(404);
        }
        return view('pages.accounting.analytics.detailsaccount',compact('dataaccount','vendorBills','Purchase'));
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
            $plans = AnalyticsAccount::findOrFail($id);
            $oldValues = $plans->toArray();
            $plans->name = $request->name;
            $plans->code = $request->code;
            $plans->plan = $request->plan;
            $plans->save();

            $uuid2 = Str::uuid()->toString();
            $userActivity = new UserActivities();
            $userActivity->id = $uuid2;
            $userActivity->modul = 'Analytics Account';
            $userActivity->id_item_modul = $plans->id; // Use the journal's ID
            $userActivity->username = Auth::user()->name;
            $userActivity->action = 'Updated';
            $userActivity->old_values = json_encode($oldValues);
            $userActivity->new_values = json_encode($plans->toArray());

            $userActivity->save();
            return redirect()->route('analytics-account.index')->with('success', 'Analytics Account Updated Successfully');
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
        $plans = AnalyticsAccount::find($id);
        $oldValues = $plans->toArray();
        $plans->delete();
        $uuid2 = Str::uuid()->toString();
        $userActivity = new UserActivities();
        $userActivity->id = $uuid2;
        $userActivity->modul = 'Analytics Account'; 
        $userActivity->id_item_modul = $id;
        $userActivity->username = Auth::user()->name; 
        $userActivity->action = 'Deleted';
        $userActivity->old_values = json_encode($oldValues);
        $userActivity->new_values = null;

        $userActivity->save();
        return redirect()->route('analytics-account.index')->with('success', 'Analytics Account Successfully Deleted');
    }
}
