<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;

class ProductController extends ApiController
{

    public function getProducts() {
        try {

            $products = Product::where("user_id", auth()->user()->id)->get();
            return $this->getSuccessResponse(["products" => $products]);

        } catch (Exception $e) {
            return $this->getFailureResponse(["message" => $e->getMessage()]);
        }
    }

    public function create(ProductRequest $request) {
        try {

            $params = $request->validated();
            $params["user_id"] = auth("api")->user()->id;
            Product::create($params);
            return $this->getSuccessResponse(["message" => "Product created."]);

        } catch (Exception $e) {
            return $this->getFailureResponse(["message" => $e->getMessage()]);
        }
    }

    public function edit(ProductRequest $request) {
        try {

            if(empty($request->id)) {
                throw new Exception("No product found.");
            }

            $product = Product::where("user_id", auth("api")->user()->id)->where("id", $request->id)->first();
            if(empty($product)) {
                throw new Exception("No product found.");
            }
            $params = $request->validated();
            $product->update($params);
            return $this->getSuccessResponse(["message" => "Product created."]);

        } catch (Exception $e) {
            return $this->getFailureResponse(["message" => $e->getMessage()]);
        }
    }


    public function getProduct(Request $request) {
        try {

            if(empty($request->id)) {
                throw new Exception("No product found.");
            }

            $product = Product::where("user_id", auth("api")->user()->id)->where("id", $request->id)->first();
            if(empty($product)) {
                throw new Exception("No product found.");
            }
            return $this->getSuccessResponse(["product" => $product]);

        } catch (Exception $e) {
            return $this->getFailureResponse(["message" => $e->getMessage()]);
        }
    }

    public function delete(Request $request) {
        try {

            if(empty($request->id)) {
                throw new Exception("No product found.");
            }

            $product = Product::where("user_id", auth("api")->user()->id)->where("id", $request->id)->first();
            if(empty($product)) {
                throw new Exception("No product found.");
            }
            $product->delete();
            return $this->getSuccessResponse(["message" => "Product deleted."]);

        } catch (Exception $e) {
            return $this->getFailureResponse(["message" => $e->getMessage()]);
        }
    }
}
