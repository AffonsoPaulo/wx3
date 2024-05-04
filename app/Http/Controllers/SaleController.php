<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleProduct;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sale = Sale::with('client', 'address', 'saleproducts.product')->get();
        if($sale->isEmpty())
            return response()->json(['message' => 'No sales found'], 404);
        return response()->json($sale);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request['paymentMethod'] = strtoupper($request['paymentMethod']);
        $request['saleProducts'] = json_decode($request['saleProducts'], true);
        $request->validate([
            'paymentMethod' => 'required|in:PIX,BOLETO,CARTAO,CARTÃO',
            'client_id' => 'required|exists:clients,id',
            'address_id' => 'required|exists:addresses,id',
            'saleProducts' => 'required|array|min:1',
            'saleProducts.*.quantity' => 'required|numeric|min:1',
            'saleProducts.*.product_id' => 'required|exists:products,id',
        ]);

        $request['shipping'] = 10;
        $totalDiscount = 0;

        $saleProducts = array_map(function($saleProduct) use (&$totalDiscount) {
            $product = Product::find($saleProduct['product_id']); // encontrar o produto no banco de dados
            $saleProduct['price'] = $product->price; // pegar preço do produto
            echo "Preço do produto " . $saleProduct['price'];
            $saleProduct['discount'] = $saleProduct['price'] * $product->discount / 100; // encontrar valor do desconto
            echo "Desconto do produto " . $saleProduct['discount'];
            $totalDiscount += $saleProduct['discount'] * $saleProduct['quantity']; // somar desconto total
            echo "Total do desconto " . $totalDiscount;
            return new SaleProduct($saleProduct);
        }, $request['saleProducts']);

        $request['discount'] = $totalDiscount;

        $request['total'] = collect($saleProducts)->sum(function($saleProduct) {
            return $saleProduct->price * $saleProduct->quantity;
        }) + $request['shipping'] - $totalDiscount;

        if($request['paymentMethod'] == 'PIX') {
            $request['total'] -= ($request['total'] * 0.1);
        }

        $sale = Sale::create($request->except('saleProducts'));

        $sale->save();
        $sale->saleproducts()->saveMany($saleProducts);

        return response()->json($sale, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sale $sale)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        //
    }
}
