<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class productController extends Controller
{
    
    public function index() {
        $products = Product::all();

        if ($products->isEmpty()) {
            $data = [
                'message' => 'No se encontraron productos',
                'status' => 404,
            ];
            return response()->json($data, 404);
        }

        return response()->json($products, 200);
    }

    public function store(request $request) {

        $validator = Validator::make($request->all(), [ // Aqui puedo crear las validaciones
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Error en la validacion de los datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
        ]);

        if (!$product) {
            $data = [
                'message' => 'Error al crear product',
                'status' => 500
            ];
            return response()->json($data, 500);
        }
        
        $data = [
            'message' => $product,
            'status' => 201,
        ];
        return response()->json($data, 201);
    }

    public function show($id) {
        $product = Product::find($id);

        if (!$product) {
            $data = [
                'message' => 'Producto no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }
        
        $data = [
            'message' => $product,
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    public function delete($id) {
        $product = Product::find($id);

        if (!$product) {
            $data = [
                'message' => 'Producto no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $product->delete();
        
        $data = [
            'message' => 'Producto eliminado',
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    public function update(Request $request, $id) {
        $product = Product::find($id);

        if (!$product) {
            $data = [
                'message' => 'Producto no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [ // Aqui puedo crear las validaciones
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Error en la validacion de los datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;

        $product->save();
        
        $data = [
            'message' => 'producto actualizado',
            'product' => $product,
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    public function updatePartial(Request $request, $id) {
        $product = Product::find($id);

        if (!$product) {
            $data = [
                'message' => 'Producto no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [ // Aqui puedo crear las validaciones
            'name' => 'max:255',
            'description' => '',
            'price' => '',
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Error en la validacion de los datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        if ($request->has('name')) {
            $product->name = $request->name;
        }

        if ($request->has('description')) {
            $product->description = $request->description;
        }

        if ($request->has('price')) {
            $product->price = $request->price;
        }
        
        $product->save();
        
        $data = [
            'message' => 'Producto actualizado',
            'product' => $product,
            'status' => 200
        ];
        return response()->json($data, 200);
    }
}
