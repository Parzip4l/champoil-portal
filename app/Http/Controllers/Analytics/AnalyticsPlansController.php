<?php

namespace App\Http\Controllers\Analytics;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\AnalyticsAccount;
use App\AnalyticsPlans;
use App\UserActivities;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class AnalyticsPlansController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dataAnalytics = AnalyticsPlans::all();
        return view('pages.accounting.analytics.plans',compact('dataAnalytics'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
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
            'applicability' => 'required'
        ]);

        $uuid = Str::uuid()->toString();
        
        $plans = new AnalyticsPlans();
        $plans->id = $uuid;
        $plans->name = $request->input('name');
        $plans->applicability = $request->input('applicability');
        $plans->save();

        $inputData = $request->all();
        $uuid2 = Str::uuid()->toString();

        $userActivity = new UserActivities();
        $userActivity->id = $uuid2;
        $userActivity->modul = 'Analytics Plans'; 
        $userActivity->id_item_modul = $plans->id = $uuid;
        $userActivity->username = Auth::user()->name; 
        $userActivity->action = 'Created';
        $userActivity->old_values = null; 
        $userActivity->new_values = json_encode($inputData);

        $userActivity->save();
        
        return redirect()->route('analytics-plans.index')->with('success', 'Analytics Plans Successfully Added');
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
            $plans = AnalyticsPlans::findOrFail($id);
            $oldValues = $plans->toArray();
            $plans->name = $request->name;
            $plans->applicability = $request->applicability;
            $plans->save();

            $uuid2 = Str::uuid()->toString();
            $userActivity = new UserActivities();
            $userActivity->id = $uuid2;
            $userActivity->modul = 'Analytics Plans';
            $userActivity->id_item_modul = $plans->id; // Use the journal's ID
            $userActivity->username = Auth::user()->name;
            $userActivity->action = 'Updated';
            $userActivity->old_values = json_encode($oldValues);
            $userActivity->new_values = json_encode($plans->toArray());

            $userActivity->save();
            return redirect()->route('analytics-plans.index')->with('success', 'Analytics Plans Updated Successfully');
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
        $plans = AnalyticsPlans::find($id);
        $oldValues = $plans->toArray();
        $plans->delete();
        $uuid2 = Str::uuid()->toString();
        $userActivity = new UserActivities();
        $userActivity->id = $uuid2;
        $userActivity->modul = 'Analytics Plans'; 
        $userActivity->id_item_modul = $id;
        $userActivity->username = Auth::user()->name; 
        $userActivity->action = 'Deleted';
        $userActivity->old_values = json_encode($oldValues);
        $userActivity->new_values = null;

        $userActivity->save();
        return redirect()->route('analytics-plans.index')->with('success', 'Analytics Plans Successfully Deleted');
    }
}
