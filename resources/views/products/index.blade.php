@extends('layouts.app')

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Products</h1>
    </div>


    <div class="card">
        <form action="{{ route('product.index') }}" method="get" class="card-header">
            
            <div class="form-row justify-content-between">
                <div class="col-md-2">
                    <input type="text" name="title" placeholder="Product Title" class="form-control">
                </div>
                <div class="col-md-2">
                    <select name="variant" id="" class="form-control">
                        <option value="">--Select A Variant</option>
                        @foreach ($product_variant as $variants)
                        <optgroup label="{{ $variants->title }}"></optgroup>
                            @php
                                $store = array();
                            @endphp
                            @foreach ($variants->productVariant as $variant)
                                @if (!in_array($variant->variant, $store))
                                    @php
                                        array_push($store,$variant->variant)
                                    @endphp
                                    <option value="{{$variant->id}}">&nbsp;&nbsp;&nbsp;{{$variant->variant}}</option>
                                @endif
                            @endforeach
                            
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Price Range</span>
                        </div>
                        <input type="text" name="price_from" aria-label="First name" placeholder="From" class="form-control">
                        <input type="text" name="price_to" aria-label="Last name" placeholder="To" class="form-control">
                    </div>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date" placeholder="Date" class="form-control">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary float-right"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>

        <div class="card-body">
            <div class="table-response">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Variant</th>
                        <th width="150px">Action</th>
                    </tr>
                    </thead>

                    <tbody>
                        @if ($products != 'null')
                            @foreach ($products as $product)
                                <tr>
                                    <td>{{ $product->id }}</td>
                                    <td style="width: 20%;">{{ $product->title }} <br> Created at: 
                                        {{ $product->created_at->format('d-M-Y') }}
                                    </td>
                                    <td style="width: 30%;">{{ $product->description }}</td>
                                    <td style="height: 50px !important; overflow:hidden !important; " id="variant">
                                        @foreach ( $product->productVariantPrice as $item )
                                    
                                            <dl class="row mb-0" >
                                                <dt class="col-sm-3 pb-0">
                                                    {{ $item->product_variant_one }}/ {{ $item->product_variant_two }}/ {{ $item->product_variant_three }}
                                                </dt>
                                                <dd class="col-sm-9">
                                                    <dl class="row mb-0">
                                                        <dt class="col-sm-4 pb-0">Price : {{ $item->price }}</dt>
                                                        <dd class="col-sm-8 pb-0">InStock : {{ $item->stock }}</dd>
                                                    </dl>
                                                </dd>
                                            </dl>
                                            
                                        @endforeach
                                        <button onclick="$('#variant').toggleClass('h-auto')" class="btn btn-sm btn-link">Show more</button>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('product.edit', 1) }}" class="btn btn-success">Edit</a>
                                        </div>
                                    </td> 
                                </tr>
                            @endforeach
                        @else no data found
                        @endif

                    {{-- <tr>
                        <td>1</td>
                        <td>T-Shirt <br> Created at : 25-Aug-2020</td>
                        <td>Quality product in low cost</td>
                        <td>
                            <dl class="row mb-0" style="height: 80px; overflow: hidden" id="variant">

                                <dt class="col-sm-3 pb-0">
                                    SM/ Red/ V-Nick
                                </dt>
                                <dd class="col-sm-9">
                                    <dl class="row mb-0">
                                        <dt class="col-sm-4 pb-0">Price : {{ number_format(200,2) }}</dt>
                                        <dd class="col-sm-8 pb-0">InStock : {{ number_format(50,2) }}</dd>
                                    </dl>
                                </dd>
                            </dl>
                            <button onclick="$('#variant').toggleClass('h-auto')" class="btn btn-sm btn-link">Show more</button>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('product.edit', 1) }}" class="btn btn-success">Edit</a>
                            </div>
                        </td>
                    </tr> --}}

                    </tbody>

                </table>
            </div>

        </div>

        <div class="card-footer">
            <div class="row justify-content-between">
                <div class="col-md-6">
                    {{-- <p>Showing 1 to 10 out of 100</p> --}}
                    <p>showing {{$products->firstItem()}} to {{$products->lastItem()}} of {{$products->total()}}</p>
                </div>
                <div class="col-md-4">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>

@endsection
