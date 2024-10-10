<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class productController extends Controller
{
    
    // public function index() {
    //     $product = Product::all();

    //     if ($product->isEmpty()) {
    //         $data = [
    //             'message' => 'No se encontraron productos',
    //             'status' => 404,
    //         ];
    //         return response()->json($data, 404);
    //     }

    //     $data = [
    //         'products' => $product,
    //     ];

    //     return response()->json($data, 200);
    // }

    public function index() {
        // Obtener los productos del usuario autenticado
        $products = Product::where('user_id', auth()->id())->get();
    
        if ($products->isEmpty()) {
            $data = [
                'message' => 'No se encontraron productos',
                'status' => 404,
            ];
            return response()->json($data, 404);
        }
    
        $data = [
            'products' => $products,
        ];
    
        return response()->json($data, 200);
    }
    
    // public function store(request $request) {

    //     $validator = Validator::make($request->all(), [ // Aqui puedo crear las validaciones
    //         'name' => 'required',
    //         'description' => 'required',
    //         'price' => 'required',
    //     ]);

    //     if ($validator->fails()) {
    //         $data = [
    //             'message' => 'Error en la validacion de los datos',
    //             'errors' => $validator->errors(),
    //             'status' => 400
    //         ];
    //         return response()->json($data, 400);
    //     }

    //     $product = Product::create([
    //         'name' => $request->name,
    //         'description' => $request->description,
    //         'price' => $request->price,
    //     ]);

    //     if (!$product) {
    //         $data = [
    //             'message' => 'Error al crear product',
    //             'status' => 500
    //         ];
    //         return response()->json($data, 500);
    //     }
        
    //     $data = [
    //         'data' => $product,
    //         'status' => 201,
    //     ];
    //     return response()->json($data, 201);
    // }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
        ]);
    
        if ($validator->fails()) {
            $data = [
                'message' => 'Error en la validación de los datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }
    
        // Obtener el ID del usuario autenticado
        $userId = auth()->id(); // Esto devuelve el ID del usuario autenticado
    
        // Crear el producto con el user_id
        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'user_id' => $userId, // Asigna el user_id
        ]);
    
        if (!$product) {
            $data = [
                'message' => 'Error al crear el producto',
                'status' => 500
            ];
            return response()->json($data, 500);
        }
        
        $data = [
            'data' => $product,
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
        
        $data = $product;
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
            'data' => 'Producto eliminado',
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
            'data' => 'producto actualizado',
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
