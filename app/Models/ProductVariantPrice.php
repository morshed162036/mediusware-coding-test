<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariantPrice extends Model
{
     protected $fillable = [
        'product_variant_one','product_variant_two','product_variant_two','product_variant_three','price','stock','product_id'
    ];
    public function variantOne(){
         return $this->belongsTo('App\Models\ProductVariant','product_variant_one')->select('variant');
    }
    public function variantTwo(){
         return $this->belongsTo('App\Models\ProductVariant','product_variant_two')->select('variant');
    }
    public function variantThree(){
         return $this->belongsTo('App\Models\ProductVariant','product_variant_three')->select('variant');
    }

}
