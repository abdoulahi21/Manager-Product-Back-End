<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProduitCommande extends Model
{
    use HasFactory;
    protected $fillable=[
        'commande_id',
        'produits_id',
        'quantite',
    ];

    public function produits(){
        return $this->belongsTo(Produit::class);
    }
    public function commande(){
        return $this->belongsTo(Commande::class);
    }


}
