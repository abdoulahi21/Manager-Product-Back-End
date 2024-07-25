<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\UserController;
use App\Models\CartItem;
use App\Models\Client;
use App\Models\Commande;
use App\Models\Produit;
use App\Models\ProduitCommande;
use App\Models\User;
use App\Notifications\CommandeNotification;
use App\Notifications\VendeurCommandeNotification;
use Illuminate\Http\Request;
use PhpParser\Node\Scalar\String_;

class CommandesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $commandes = Commande::with('client')->get();
        return response()->json($commandes);
    }
    public function getTopSellingProducts()
    {
        try {
            // Assuming 'orders' table contains 'product_id' and 'quantity' columns
            $products = DB::table('products')
                ->join('order_items', 'products.id', '=', 'order_items.product_id')
                ->select('products.nom', DB::raw('SUM(order_items.quantity) as quantite_vendue'))
                ->groupBy('products.id')
                ->orderBy('quantite_vendue', 'desc')
                ->take(10) // Get top 10 selling products
                ->get();

            return response()->json([
                'success' => true,
                'products' => $products
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des produits les plus vendus: ' . $e->getMessage()
            ], 500);
        }
    }
    public function store(Request $request)

    {
        $request->validate([
            'nomcomplet' => 'required',
            'email' => 'required',
            'numeroTelephone' => 'required',
            'adresse' => 'required',
            'total_price' => 'required',
        ]);
        $client=new Client();
        $client->nomcomplet=$request->input('nomcomplet');
        $client->email=$request->input('email');
        $client->numeroTelephone=$request->input('numeroTelephone');
        $client->adresse=$request->input('adresse');
        $client->save();

        // Partie 1 : Préfixe fixe pour les commandes
        $prefixe = "CMD";

    // Partie 2 : Année actuelle
        $annee = date("Y");

    // Partie 3 : Mois actuel
        $mois = date("m");

    // Partie 4 : Numéro de commande aléatoire
        $num_commande = rand(1000, 9999);

// Concaténation des parties pour former le matricule de commande
        $matricule_commande = $prefixe . "-" . $annee . $mois . "-" . $num_commande;
        $commande = new Commande();
        $commande->client_id = $client->id;
        $commande->numerocommande = $matricule_commande;
        $commande->total_price = $request->input('total_price');
        $commande->status = 'Non expédiée';
        $commande->save();

        $cartitem=CartItem::with('product')->get();
        foreach ($cartitem as $item) {
            $produitCommande = new ProduitCommande();
            $produitCommande->order_id = $commande->id;
            $produitCommande->product_id = $item->product->id;
            $produitCommande->quantite = $item->quantity;

            $produitCommande->save();

            $stock=$item->product;
            $newstock=$stock->quantite-$item->quantity;
            $stock->update(['quantite'=>$newstock]);
            $item->delete();
        }
        $client->notify(new CommandeNotification($commande));
        return response()->json([
            'message' => 'Commande créée avec succès',
            'code' => 200,
        ]);

    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */

   /* public function downloadPDF(string $id)
    {
        $commande= Commande::find($id);

        $pdf = PDF::loadView('commande.viewPdf', array('commande' =>  $commande))
            ->setPaper('a4', 'portrait');

        return $pdf->download('commandes-details.pdf');
    }
    public function viewPDF(string $id)
    {
        $commande = Commande::find($id);

        $pdf = PDF::loadView('commande.viewPdf', array('commande' =>  $commande))
            ->setPaper('a4', 'portrait');

        return $pdf->stream();

    }*/



    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        //
        $commande=Commande::with('client')->find($id);
        $commande->load('items');
        $commande->load('items.product');
        return response()->json($commande);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }
    public function validercommande(string $id)
    {
        $commande=Commande::find($id);
        $commande->update(['status'=>'Expédiée']);
        return response()->json([
            'message' => 'Commande validée avec succès',
            'code' => 200,
        ]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'date' => 'required',
            'quantites' => 'required|array',
            'quantites.*' => 'required|integer|min:1',
        ]);
        // Récupérer la commande à modifier
        $commande = Commande::findOrFail($id);

        // Mettre à jour les données de la commande
        $commande->client_id = $request->input('client_id');
        $commande->date = $request->input('date');
        $commande->save();

        // Mettre à jour les quantités des produits dans la commande
        foreach ($request->input('quantites') as $produitId => $quantite) {
            $commande->produits()->updateExistingPivot($produitId, ['quantite' => $quantite]);
        }

        return response()->json([
            'message' => 'Commande modifiée avec succès',
            'code' => 200,
        ]);
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $commme=Commande::find($id);
        $commme->delete();
        return response()->json([
            'message' => 'Commande supprimée avec succès',
            'code' => 200,
        ]);
    }

}
