<?php

namespace App\Http\Controllers;

use App\Models\ProductApi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductApiController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $products = ProductApi::all()->toArray();

        $response=[
            "Data"=> mb_convert_encoding($products, 'UTF-8', 'UTF-8'),
            "status"=> "true",
            "Message"=>"Product List"
        ];

        $code=200;
        return response()->json($response,$code);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
            'image' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $product = ProductApi::create($input);
        return response()->json([
            "success" => true,
            "message" => "Product created successfully.",
            "data" => $product
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $product = ProductApi::find($id)->toArray();
        if (!$product) {
            return $this->sendError('Product not found.');
        }else{
            return response()->json([
                "success" => true,
                "message" => "Product retrieved successfully.",
                "data" => mb_convert_encoding($product, 'UTF-8', 'UTF-8'),
            ]);
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param ProductApi $product
     * @return JsonResponse
     */
    public function update(Request $request, ProductApi $product)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
            'image' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $product->name = $input['name'];
        $product->description = $input['description'];
        $product->price = $input['price'];
        $product->image = $input['image'];
        $product->save();

        return response()->json([
            "success" => true,
            "message" => "Product updated successfully.",
            "data" => $product
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(ProductApi $product)
    {
        $product->delete();
        return response()->json([
            "success" => true,
            "message" => "Product deleted successfully.",
            "data" => $product
        ]);
    }
}
