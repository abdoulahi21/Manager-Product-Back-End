<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Commande;
use App\Models\Produit;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function index()
    {
        try {
            //nombre de clients
            $clients = Client::count();
            //nombre de commandes
            $commandes = Commande::count();
            //nombre de produits
            $produits = Produit::count();
            //montant total des commandes
            $total = Commande::sum('total_price');
            return response()->json([
                'success'=>true,
                'clients' => $clients,
                'commandes' => $commandes,
                'produits' => $produits,
                'total' => $total
            ],200);
        }catch (\Exception $e){
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
