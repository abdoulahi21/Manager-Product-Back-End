<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProduitCommande extends Model
{
    use HasFactory;
    protected $table='produits_commandes';
    protected $fillable=[
        'order_id',
        'product_id',
        'quantite',
        'price',
    ];

    public function order()
    {
        return $this->belongsTo(Commande::class);
    }

    public function product()
    {
        return $this->belongsTo(Produit::class);
    }

}
