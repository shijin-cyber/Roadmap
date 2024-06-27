<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Validate the request data
            $request->validate([
                'name' => 'required|string|max:255',
            ]);

            // Create a new product
            $product = Product::create([
                'name' => $request->name,
            ]);

            // Return a JSON response indicating success
            return response()->json([
                'success' => true,
                'product' => $product,
            ]);

        } catch (\Exception $e) {
            // Log the error for debugging purposes
            Log::error('Error creating product: ' . $e->getMessage());

            // Return a JSON response indicating failure
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the product.',
            ], 500); // HTTP status 500: Internal Server Error
        }
    }
    public function getProducts()
    {
        $products = Product::all(); // Fetch all products from the database

        return response()->json(['products' => $products]);
    }
}
