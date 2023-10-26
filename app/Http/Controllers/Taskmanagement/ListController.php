<?php

namespace App\Http\Controllers\Taskmanagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ModelCG\List_task;

class ListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $data['records']=List_task::all();
        return view('pages.operational.list_task.index',$data);
    }

    public function list_task($id)
    {
        $data['records']=List_task::all();
        $data['id_master']=$id;
        return view('pages.operational.list_task.index',$data);
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
        $request->validate([
            'task' => 'required'
        ]);

        $list_task = new List_task();
        $list_task->task = $request->task;
        $list_task->id_master = $request->id_master;    
        $list_task->save();

        return redirect()->route('add_task', ['id' => $request->id_master])->with('success', 'List Task Successfully');
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
        $list_task = List_task::find($id);
        $list_task->delete();
        return redirect()->route('add_task', ['id' => $list_task->id_master])->with('success', 'List Task Successfully Deleted');
    }
}
