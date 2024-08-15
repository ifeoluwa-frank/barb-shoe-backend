<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    //
    public function index($user_id){
        $cartItem = CartItem::where('user_id', $user_id)->get();
        //$jsonData['cartItem'] = $cartItem;
        $finalResponse = [];
        foreach ($cartItem as $item) {
            $jsonData['cartItem'] = $item;
            $cartItemProductID = $item->product_id;
            $cartItemProduct = Inventory::find($cartItemProductID);
            $image = $cartItemProduct['image'];
            $imgBaseUrl = env('APP_URL') . '/storage';
            $imgUrl = asset('storage/' . $image);
            if($imgBaseUrl === $imgUrl){
                $cartItemProduct['image'] = 'https://placehold.co/600x400';
            } else {
                $cartItemProduct['image'] = $imgUrl;
            }
            $jsonData['cartItemProduct'] = $cartItemProduct;

            array_push($finalResponse, $jsonData);
        }
        return response()->json($finalResponse);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
           'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:inventory,id',
            'quantity' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'type' => 'validation',
                'errors' => $validator->errors(),
            ],422);
        }

        $cartItem = CartItem::create([
            'user_id' => $request->user_id,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Product added to cart',
            'inventory' => $cartItem,
        ], 201);
    }
}
