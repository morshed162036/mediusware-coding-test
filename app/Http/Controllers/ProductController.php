<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Variant;
use App\Models\ProductVariant;
use App\Models\ProductVariantPrice;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        // dd($request->title);
        // $products = Product::with('productVariantPrice')->paginate(3);
        $query = Product::query();
        //dd($query);
        if(isset($request->title) && ($request->title != null)){
            //dd($request->title);
            $query->where('title','LIKE',"%$request->title%");
        }
        if(isset($request->variant) && ($request->variant != null)){
            $query->whereHas('productVariantPrice', function($q) use ($request){
                $q->where('product_variant_one',$request->variant)->orWhere('product_variant_two',$request->variant)->orWhere('product_variant_three',$request->variant);
            });
        }
        if(isset($request->price_from) && ($request->price_from != null)){
            $query->whereHas('productVariantPrice', function($q) use ($request){
                $q->where('price','>=',$request->price_from);
            });
        }
        if(isset($request->price_to) && ($request->price_to != null)){
            $query->whereHas('productVariantPrice', function($q) use ($request){
                $q->where('price','<=',$request->price_to);
            });
        }

        $products = $query->paginate(3);
        // foreach($products as $product){
        //     foreach($product->productVariantPrice as $item){
        //         print_r($item->product_variant_one);
        //     }  
        // }
        // die();
        //dd($products);
        $product_variant = Variant::with('productVariant')->get();
        //$product_variant = ProductVariant::with('Variant')->distinct()->get()->toArray();
        //dd($product_variant);
        return view('products.index',compact('products','product_variant'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        $variants = Variant::all();
        return view('products.create', compact('variants'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
         $variantOpt = array();
         $variantPreview = array();
         $previewCount = 0;

         $product = Product::create(['title'=>$request->product_name,'sku'=>$request->product_sku,'description'=>$request->product_description ]);
         $productId = $product->id;
         
        if(count($request->product_variant) > 0){
            foreach ($request->product_variant as $key => $variant){
                $variant_id = $variant['option'];

                 if(count($variant['value']) > 0){
                     foreach($variant['value'] as $value){
                        
                        $variant = ProductVariant::create(['variant'=>$value,'variant_id'=>$variant_id,'product_id'=>$productId]);
                        $variantOpt[$key][]=$variant->id;
                     }
                 }
            }
        }
        
        $previewCount = count($request->product_preview);
        $c = 1;
        
        for ($one=0; $one < count($variantOpt[0]) ; $one++) { 
            if(isset($variantOpt[1])){
                
                for ($two=0; $two < count($variantOpt[1]) ; $two++) { 
                    if(isset($variantOpt[2])){
                        for ($three=0; $three < count($variantOpt[2]) ; $three++) { 
                            if(($previewCount - $c) >= 0){
                                    ProductVariantPrice::create(['product_variant_one'=>$variantOpt[0][$one],'product_variant_two'=>$variantOpt[1][$two],'product_variant_three'=>$variantOpt[2][$three],'price'=>$request->product_preview[$c-1]['price'],'stock'=>$request->product_preview[$c-1]['stock'],'product_id'=>$productId]);
                                    $c++;
                            }
                        }
                    }
                    else{
                        if(($previewCount - $c) >= 0){
                                    ProductVariantPrice::create(['product_variant_one'=>$variantOpt[0][$one],'product_variant_two'=>$variantOpt[1][$two],'price'=>$request->product_preview[$c-1]['price'],'stock'=>$request->product_preview[$c-1]['stock'],'product_id'=>$productId]);
                                    $c++;
                        }
                    }
                
                }
            }
            else{
                if(($previewCount - $c) >= 0){
                        ProductVariantPrice::create(['product_variant_one'=>$variantOpt[0][$one],'price'=>$request->product_preview[$c-1]['price'],'stock'=>$request->product_preview[$c-1]['stock'],'product_id'=>$productId]);
                        $c++;
                }
            }
            
        }
        return redirect()->route('product.index');
    }


    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show($product)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $variants = Variant::all();
        return view('products.edit', compact('variants'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
