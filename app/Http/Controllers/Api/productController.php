<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Storage;

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
    
    // public function store(Request $request) {
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required',
    //         'description' => 'required',
    //         'price' => 'required|numeric',
    //     ]);
    
    //     if ($validator->fails()) {
    //         $data = [
    //             'message' => 'Error en la validación de los datos',
    //             'errors' => $validator->errors(),
    //             'status' => 400
    //         ];
    //         return response()->json($data, 400);
    //     }
    
    //     // Obtener el ID del usuario autenticado
    //     $userId = auth()->id(); // Esto devuelve el ID del usuario autenticado
    
    //     // Crear el producto con el user_id
    //     $product = Product::create([
    //         'name' => $request->name,
    //         'description' => $request->description,
    //         'price' => $request->price,
    //         'user_id' => $userId, // Asigna el user_id
    //     ]);
    
    //     if (!$product) {
    //         $data = [
    //             'message' => 'Error al crear el producto',
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
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validación de la imagen
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
        $userId = auth()->id();
    
        // Procesar la imagen
        if ($request->hasFile('image')) {
            // Guardar la imagen en el disco público y obtener la ruta
            $imagePath = $request->file('image')->store('images', 'public'); // Guarda la imagen en storage/app/public/images
    
            $product = Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'image_path' => $imagePath, // Guarda la ruta de la imagen
                'user_id' => $userId,
            ]);
        }
    
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

    // public function show($id) {
    //     // Buscar el producto del usuario autenticado
    //     $product = Product::where('id', $id)->where('user_id', auth()->id())->first();
    
    //     if (!$product) {
    //         $data = [
    //             'message' => 'Producto no encontrado o no pertenece al usuario',
    //             'status' => 404
    //         ];
    //         return response()->json($data, 404);
    //     }
        
    //     return response()->json($product, 200);
    // }
    

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

    // public function updatePartial(Request $request, $id) {
    //     $product = Product::find($id);

    //     if (!$product) {
    //         $data = [
    //             'message' => 'Producto no encontrado',
    //             'status' => 404
    //         ];
    //         return response()->json($data, 404);
    //     }

    //     $validator = Validator::make($request->all(), [ // Aqui puedo crear las validaciones
    //         'name' => 'max:255',
    //         'description' => '',
    //         'price' => '',
    //     ]);

    //     if ($validator->fails()) {
    //         $data = [
    //             'message' => 'Error en la validacion de los datos',
    //             'errors' => $validator->errors(),
    //             'status' => 400
    //         ];
    //         return response()->json($data, 400);
    //     }

    //     if ($request->has('name')) {
    //         $product->name = $request->name;
    //     }

    //     if ($request->has('description')) {
    //         $product->description = $request->description;
    //     }

    //     if ($request->has('price')) {
    //         $product->price = $request->price;
    //     }
        
    //     $product->save();
        
    //     $data = [
    //         'message' => 'Producto actualizado',
    //         'product' => $product,
    //         'status' => 200
    //     ];
    //     return response()->json($data, 200);
    // }
    public function updatePartial(Request $request, $id) {
        $product = Product::find($id);
    
        if (!$product) {
            $data = [
                'message' => 'Producto no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }
    
        $validator = Validator::make($request->all(), [
            'name' => 'max:255',
           'description' => 'nullable|string',
            'price' => 'nullable|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
    
        // Manejar la imagen
        if ($request->hasFile('image')) {
            // Eliminar la imagen anterior si existe
            if ($product->image_path) {
                Storage::delete($product->image_path);
            }
    
            // Guardar la nueva imagen y actualizar el campo image_path
            $path = $request->file('image')->store('products', 'public');
            $product->image_path = $path;
        }
        // Log::info("Datos del producto antes de guardar: ", $product->toArray());
        $product->save();
        
        $data = [
            'message' => 'Producto actualizado',
            'product' => $product,
            'status' => 200
        ];
        return response()->json($data, 200);
    }
}
