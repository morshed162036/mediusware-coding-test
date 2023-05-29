<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\productVariant;
use App\Models\productVariantPrice;

class Product extends Model
{
    protected $fillable = [
        'title', 'sku', 'description'
    ];

    public function productVariant(){
        return $this->hasMany(productVariant::class,'product_id');
    }
    public function productVariantPrice(){
        return $this->hasMany(productVariantPrice::class,'product_id')->with('variantOne','variantTwo','variantThree');
    }

}
