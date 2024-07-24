<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function order() : BelongsTo
    {
        return $this->belongsTo(Commande::class, 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(Produit::class);
    }
}
