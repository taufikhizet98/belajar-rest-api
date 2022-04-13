<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'product_name' => 'required|max:50',
            'product_type' => 'required|in:snack,drink,fruit,drug,groceries,cigarette,make-up',
            'product_price' => 'required|numeric',
            'expired_at' => 'required|date'
        ]);
        if($validator->fails()){
            return response()->json($validator->message())->setStatusCode(422);
        }
        $payload = $validator->validated();
        Product::create([
            'product_name' => $payload['product_name'],
            'product_type' => $payload['product_type'],
            'product_price' => $payload['product_price'],
            'expired_at' => $payload['expired_at']
        ]);
        return response()->json([
            'msg' => 'Data Produk berhasil disimpan'
        ],201);
    }

    public function showAll(){
        $products = Product::all();
        
        return response()->json([
            'msg' => 'Data Produk Keseluruhan',
            'data' => $products
        ],200);
    }

    public function showById($id){
        $product = Product::where('id',$id)->first();
        if($product){
            return response()->json([
                'msg' => 'Data Produk dengan ID:'. $id,
                'data' => $product
            ],200);
        }
        return response()->json([
            'msg' => 'Data produk dengan ID:'.$id.'tidak ditemukan'
        ],404);
    }

    public function showByName($product_name){
        $product = Product::where('product_name','LIKE','%'.$product_name.'%')->get();
        if($product->count() > 0){
            return response()->json([
                'msg' => 'Data Produk dengan nama yang mirip:'.$product_name,
                'data' => $product
            ],200);
        }
        return response()->json([
            'msg' => 'Data produk dengan nama yang mirip:'.$product_name.'tidak ditemukan',
        ],404);
    }

    public function update(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'product_name' => 'required|max:50',
            'product_type' => 'required|in:snack,drink,fruit,drug,groceries,cigarette,make-up',
            'product_price' => 'required|numeric',
            'expired_at' => 'required|date'
        ]);
        if($validator->fails()){
            return response()->json($validator->messages())->setStatusCode(422);
        }
        $payload = $validator->validated();
        Product::where('id',$id)->update([
            'product_name' => $payload['product_name'],
            'product_type' => $payload['product_type'],
            'product_price' => $payload['product_price'],
            'expired_at' => $payload['expired_at']
        ]);
        return response()->json([
            'msg' => 'Data Produk berhasil diubah'
        ],201);
    }

    public function delete($id){
        $product = Product::where('id',$id)->get();
        if($product){
            Product::where('id',$id)->delete();
            return response()->json([
                'msg' => 'Data produk dengan ID:'.$id.'berhasil dihapus'
            ],200);
        }
        return response()->json([
            'msg' => 'data produk dengan ID:'.$id.'gagal dihapus'
        ],404);
    }
}
