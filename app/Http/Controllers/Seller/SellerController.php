<?php

namespace App\Http\Controllers\Seller;

use App\Seller;
use App\Http\Controllers\ApiController;

class SellerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vendedores = Seller::has('products')->get();

        return response()->json(['data' => $vendedores], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function show(Seller $seller)
    {
        $vendedor = Seller::has('products')->findOrFail($seller->id);

        return response()->json(['data' => $vendedor], 200);
    }
}
