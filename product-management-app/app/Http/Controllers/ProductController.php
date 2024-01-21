<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Helpers\RoleHelper;



class ProductController extends Controller

  
{
    public function index()
    
    {
        if (RoleHelper::SuperAdmin()) {
        $products = Product::all();
        return response()->json(['products'=>$products]);
        }
        return response()->json(['error' => 'Unauthorized'], 403);

    }

    public function store(Request $request)
    {
        if (RoleHelper::SuperAdmin()) {
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'size' => 'required|string',
            
        ]);

        $user = Auth::guard('api')->user();
        $product = Product::create($request->all());
        return response()->json($product, 201);
    }
    return response()->json(['error' => 'Unauthorized'], 403);    
    }

    public function show($product)
    
    {
        if (RoleHelper::SuperAdmin()) {
       
        $product=Product::findOrfail($product);
        return response()->json(['products'=>$product]);
    }
      
    return response()->json(['error' => 'Unauthorized'], 403);
             
      
    }

    public function update(Request $request, $product)
    
    {
        if (RoleHelper::SuperAdmin()) {
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'size' => 'required|string',
       
        ]);
        $product=Product::findOrfail($product);

      
        $product->update($request->all());

        return response()->json(['product'=>$product]);
    }
      
    return response()->json(['error' => 'Unauthorized'], 403);
             
      
    }

    public function destroy($product)
{
    if (RoleHelper::SuperAdmin()) {
        $product = Product::findOrFail($product);
        
        
        $product->delete();
        
        return response()->json(null, 204);
    }
    
    return response()->json(['error' => 'Unauthorized'], 403);
}


}
