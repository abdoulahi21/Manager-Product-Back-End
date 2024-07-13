<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;
    protected $fillable=[
        'clients_id',
        'date',
    ];
    public function client()
    {
        return $this->belongsTo(Client::class, 'clients_id');
    }

    public function produits()
    {
        return $this->belongsToMany(Produit::class,'produits_commandes',
            'commande_id','produits_id')->withPivot('quantite');
    }

    public function calculerMontantTotal()
    {
        $montantTotal = 0;

        foreach ($this->produits as $produit) {
            $montantTotal += $produit->prix * $produit->pivot->quantite;
        }

        return $montantTotal;
    }

}
