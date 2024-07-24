<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Produit;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function addToCart(String $id)
    {
        $panier=CartItem::create([
            'product_id' => $id,
            'quantity' => 1
        ]);
        return response()->json($panier);
    }

    public function getCart()
    {
        $cart = CartItem::with('product')->get();
        return response()->json($cart);
    }

    public function updateCartItem(Request $request, $productId)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
        }

        return response()->json($cart);
    }

    public function destroy(String $id)
    {
        $cart = CartItem::find($id);
       $c= $cart->delete();
        return response()->json($c);
    }

    }

