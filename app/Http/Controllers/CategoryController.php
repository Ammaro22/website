<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function create(Request $request)
    {

        if (!$request->user() || $request->user()->type !== 'Admin') {
            return response()->json([
                'message' => __('messages.operation_failed'),
                'error' => 'Unauthorized action.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => __('messages.operation_failed'),
                'errors' => $validator->errors()
            ], 422);
        }

        $category = new Category();
        $category->name = $request->name;
        $category->save();

        return response()->json([
            'message' => __('messages.operation_success'),
            'data' => $category,
        ], 201);
    }

    public function update(Request $request, $id = null)
    {
        if (is_null($id)) {
            return response()->json([
                'message' => __('messages.operation_failed'),
                'error' => __('messages.not_found')
            ], 404);
        }

        if (!$request->user() || $request->user()->type !== 'Admin') {
            return response()->json([
                'message' => __('messages.operation_failed'),
                'error' => 'Unauthorized action.'
            ], 403);
        }


        $category = Category::findOrFail($id);

        if (!$category) {
            return response()->json([
                'message' => __('messages.operation_failed'),
                'error' => __('messages.not_found')
            ], 404);
        }


        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => __('messages.operation_failed'),
                'errors' => $validator->errors()
            ], 422);
        }
        $category->name = $request->name ?? $category->name;
        $category->save();

        return response()->json([
            'message' => __('messages.operation_success'),
            'data' => $category,
        ]);
    }

    public function index()
    {
        $categories = Category::all();
        return response()->json([
            'message' => __('messages.operation_success'),
            'data' => $categories,
        ]);
    }

    public function destroy(Request $request, $id = null)
    {

        if (is_null($id)) {
            return response()->json([
                'message' => __('messages.operation_failed'),
                'error' => __('messages.not_found')
            ], 404);
        }


        if (!$request->user() || $request->user()->type !== 'Admin') {
            return response()->json([
                'message' => __('messages.operation_failed'),
                'error' => __('messages.unauthorized')
            ], 403);
        }


        $category = Category::find($id);


        if (!$category) {
            return response()->json([
                'message' => __('messages.operation_failed'),
                'error' => __('messages.not_found')
            ], 404);
        }


        $category->delete();

        return response()->json([
            'message' => __('messages.delete_success'),
        ], 200);
    }
}
