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
            'saleProducts.*.variation_id' => 'required|exists:variations,id',
        ]);

        $request['shipping'] = 10;
        $totalDiscount = 0;

        foreach($request['saleProducts'] as $saleProduct) {
            $product = Product::find($saleProduct['product_id']);

            $variation = $product->variation()->where('id', $saleProduct['variation_id'])->first();
            if(is_null($variation)) {
                return response()->json(['message' => 'Variation not found'], 404);
            }
            if($variation->quantity < $saleProduct['quantity']) {
                return response()->json(['message' => 'Product out of stock'], 400);
            }
        }

        $saleProducts = [];
        foreach ($request['saleProducts'] as $saleProduct) {
            $product = Product::find($saleProduct['product_id']);

            $variation = $product->variation()->where('id', $saleProduct['variation_id'])->first();
            $variation->update(['quantity' => $variation->quantity - $saleProduct['quantity']]);

            $saleProduct['price'] = $product->price;
            $saleProduct['discount'] = $saleProduct['price'] * $product->discount / 100;
            $totalDiscount += $saleProduct['discount'] * $saleProduct['quantity'];
            $saleProducts[] = new SaleProduct($saleProduct);
        }

        $request['discount'] = $totalDiscount;

        $request['total'] = collect($saleProducts)->sum(function($saleProduct) {
            return $saleProduct->price * $saleProduct->quantity;
        }) + $request['shipping'] - $totalDiscount;

        if($request['paymentMethod'] == 'PIX') {
            $request['total'] -= ($request['total'] * 0.1);
        }

        $sale = Sale::create($request->except('saleProducts'));

        $sale->saleproducts()->saveMany($saleProducts);
        $sale->save();

        return response()->json(['message' => 'Sale created successfully', $sale], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $sale = Sale::with('client', 'address', 'saleproducts.product')->find($id);
        if($sale == null)
            return response()->json(['message' => 'No sales found'], 404);
        return response()->json($sale);
    }

    /**
     * Update the specified resource in storage.
     */
    public function bestSellers() {
        $products = SaleProduct::select('product_id', SaleProduct::raw('SUM(quantity) as total'))
            ->groupBy('product_id')
            ->orderBy('total', 'desc')
            ->get();
        if($products->isEmpty())
            return response()->json(['message' => 'No sales found'], 404);
        return response()->json($products);
    }
}
