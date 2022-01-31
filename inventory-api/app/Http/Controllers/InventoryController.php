<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(){
        return response([
            'inventory' =>Inventory::orderby('created_at', 'desc')->get()
        ], 200);
    }

    public function show($id){
        return response([
            'inventory' =>Inventory::where('id', $id)->get()
        ], 200);
    }

    public function store(Request $request){

        $attrs = $request->validate([
            'name' => 'string|required',
            'type_of_service' => 'string|required|max:32',
            'tax' => 'integer|required',
            'cost' => 'integer|required',
            'due_date'=> 'string|required',
            'status' => 'string|required',
        ]);

        $inv = Inventory::create([
            'name' => $attrs['name'],
            'type_of_service' => $attrs['type_of_service'],
            'tax' => $attrs['tax'],
            'cost' => $attrs['cost'],
            'due_date' => $attrs['due_date'],
            'status' => $attrs['status'],
            'user_id' => auth()->user()->id
        ]);

        return response([
            'message' => 'Post Shared',
            'inventory' => $inv
        ], 200);

    }

    public function update(Request $request, $id){

        $inventory = Inventory::find($id);

        if(!$inventory){
            return response([
                'message' => 'inventory Not Found'
            ],403);
        }

        if($inventory->user()->id != auth()->user()->id){
            return response([
                'message' => 'Permission Declined'
            ],403);
        }

        $attrs = $request->validate([
            'name' => 'string|required',
            'type_of_service' => 'string|required|max:32',
            'tax' => 'integer|required',
            'cost' => 'integer|required',
            'due_date'=> 'string|required',
            'status' => 'string|required',
        ]);

        $inventory->update([
            'name' => $attrs['name'],
            'type_of_service' => $attrs['type_of_service'],
            'tax' => $attrs['tax'],
            'cost' => $attrs['cost'],
            'due_date' => $attrs['due_date'],
            'status' => $attrs['status'],
        ]);

        return response([
            'message' => 'inventory Updated',
            'inventory' => $inventory
        ], 200);

    }

    public function destroy($id){

        $inventory = Inventory::find($id);

        if(!$inventory){
            return response([
                'message' => 'inventory Not Found'
            ],403);
        }

        if($inventory->user()->id != auth()->user()->id){
            return response([
                'message' => 'Permission Declined'
            ],403);
        }

        $inventory->delete;

        return response([
            'message' => 'inventory Removed',
            'inventory' => $inventory
        ], 200);
    }
}
