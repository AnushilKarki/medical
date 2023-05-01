<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medicine;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class MedicineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return Medicine::select('id','name','image','category','price')->get();
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
            'name'=>'required',
            'category'=>'required',
            'image'=>'required|image',
            'price'=>'required'
        ]);
        try{
            $medicine = new Medicine();
            $medicine->name = $request->name;
            $medicine->price = $request->price;
            $medicine->category = $request->category;
            $imageName = time().'.'.$request->image->extension();  
            $request->image->storeAs('images', $imageName);
            $request->image->move(public_path('images'), $imageName);
            $medicine->image = $imageName;
            $medicine->save();
            return response()->json([
                'message'=>'Medicine Created Successfully!!'
            ]);
        }catch(\Exception $e){
            \Log::error($e->getMessage());
            return response()->json([
                'message'=>'Something goes wrong while creating a Medicine!!'
            ],500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Medicine $id)
    {
        //
        return response()->json([
            'medicine'=>$id
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
        $request->validate([
            'qty'=>'required',
            'supplier_name'=>'required',
            'expiry_date'=>'nullable',
            'batch_number'=>'required'
        ]);
        $medicine = Medicine::find($id);
        $medicine->qty = $request->Qty;
        $medicine->supplier_name = $request->supplier_name;
        $medicine->expiry_date = $request->expiry_date;
        $medicine->batch_number = $request->batch_number;
        $medicine->save();
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
