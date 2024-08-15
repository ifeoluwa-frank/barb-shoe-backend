<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Support\Facades\Storage;
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
        $disk = Storage::disk('public');
        $updatedData = [];
        $jsonData = [];
        foreach ($inventory as $inv){
            $imgBaseUrl = env('APP_URL') . '/storage';
            $imgUrl = asset('storage/' . $inv->image);
            $updatedData['id'] = $inv->id;
            $updatedData['name'] = $inv->name;
            $updatedData['description'] = $inv->description;
            $updatedData['price'] = $inv->price;
            $updatedData['quantity'] = $inv->quantity;
            $updatedData['color'] = $inv->color;
            $updatedData['size'] = $inv->size;
            if($imgBaseUrl === $imgUrl){
                $updatedData['image'] = 'https://placehold.co/600x400';
            } else {
                $updatedData['image'] = $imgUrl;
            }
            array_push($jsonData, $updatedData);

        }
        return response()->json($jsonData);
        //print_r($jsonData);
    }

    public function getProductById($id) {
        $product = Inventory::find($id);

        $imgBaseUrl = env('APP_URL') . '/storage';

        if ($product) {
            $product->image = asset('storage/' . $product->image);
            if($product->image === $imgBaseUrl){
                $product->image = 'https://placehold.co/600x400';
            }
            return response()->json([
                'status' => true,
                'product' => $product,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Product not found'
            ], 404);
        }
    }

    public function updateInventory(Request $request, $id){

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'color' => 'required',
            'size' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        //return $request->all();
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'type' => 'validation',
                'errors' => $validator->errors(),
            ], 422);
        }

        $product = Inventory::find($id);

        if($product){
            $imagePath = $product->image;
            if ($request->hasFile('image')) {
                // Delete the old image if it exists
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                // Store the new image and update the path
                $imagePath = $request->file('image')->store('product_images', 'public');
            }

            $product->name = $request->input('name');
            $product->description = $request->input('description');
            $product->price = $request->input('price');
            $product->quantity = $request->input('quantity');
            $product->color = $request->input('color');
            $product->size = $request->input('size');
            $product->image = $imagePath;

            $product->fill($request->only(['name', 'description', 'price', 'quantity', 'color', 'size']));
            $product->image = $imagePath;

            $product->save();

            return response()->json([
                'status' => true,
                'message' => 'Product updated successfully!',
                'inventory' => $product,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'type' => 'unknown',
                'message' => 'Product not found'
            ], 404);
        }
    }

    public function deleteInventory($id)
    {
        $product = Inventory::find($id);

        if($product){
            $product->delete();

            return response()->json([
                'status' => true,
                'message' => 'Product deleted successfully!',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'type' => 'unknown',
                'message' => 'Product not found'
            ]);
        }
    }
}
