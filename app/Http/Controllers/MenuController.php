<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Menu;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = Menu::where('parent_id',0)->get();
        

        $data['result']=$records;
        return view('pages.menu.index',$data);
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
        $data = $request->all();
        $insert=[
            "parent_id"=>$data['parent_id'],
            "title"=>$data['title'],
            "url"=>$data['url'],
            "description"=>$data['description'],
            "menu_order"=>$data['menu_order'],
            "is_icon"=>$data['is_icon']
        ];

        Menu::insert($insert);
        return redirect()->route('menu.index')->with('success', 'Menu Successfully Create');
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

    public function parentChild($id){
        $error=true;
        $records = Menu::where('parent_id',$id)->orderBy('menu_order','desc')->get();

        if($records){
            $error=false;
        }
        return response()->json([
            'error'=>$error,
            'result'=>$records    
        ]);
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
