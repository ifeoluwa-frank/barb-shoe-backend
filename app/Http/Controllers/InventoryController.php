<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class InventoryController extends Controller
{

    //
    public function addInventory(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'color' => 'required',
            //'color.*' => 'required|string',
            'size' => 'required',
            //'size.*' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        //return $request->all();
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'type' => 'validation',
                'errors' => $validator->errors(),
            ],422);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('product_images', 'public');
        }

        $inventory = Inventory::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'color' => $request->color,
            'size' => $request->size,
            'image' => $imagePath,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Product created successfully!',
            'inventory' => $inventory,
        ], 201);
    }

    public function index()
    {
        $inventory = Inventory::all();
        return response()->json($inventory);
    }
}
