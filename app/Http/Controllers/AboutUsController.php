<?php

namespace App\Http\Controllers;

use App\Models\About_us;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AboutUsController extends Controller
{
    public function store(Request $request)
    {

        if (!$request->user() || $request->user()->type !== 'Admin') {
            return response()->json([
                'message' => __('messages.operation_failed'),
                'error' => 'messages.Unauthorized'
            ], 403);
        }

        $validatedData = $request->validate([
            'content' => 'required|string',
        ]);


        $aboutUs = new About_Us();
        $aboutUs->content = $validatedData['content'];
        $aboutUs->save();

        return response()->json([
            'message' => __('messages.operation_success'),
        ], 201);
    }

    public function update(Request $request, $id)
    {

        if (!$request->user() || $request->user()->type !== 'Admin') {
            return response()->json([
                'message' => __('messages.operation_failed'),
                'error' => 'messages.Unauthorized'
            ], 403);
        }

        $request->validate([
            'content' => 'required|string',
        ]);


        $aboutUs = About_Us::find($id);

        if (!$aboutUs) {
            return response()->json([
                'message' => __('messages.operation_failed'),
                'error' => 'messages.NotFound'
            ], 404);
        }

        $aboutUs->content = $request->input('content');
        $aboutUs->save();

        return response()->json([
            'message' => __('messages.operation_success'),
        ], 200);
    }

    public function show()
    {

        $aboutUs = About_Us::all();

        if (!$aboutUs) {
            return response()->json([
                'message' => __('messages.operation_failed'),
                'error' => 'messages.NotFound'
            ], 404);
        }

        return response()->json([
            'message' => __('messages.operation_success'),
            'data' => $aboutUs,
        ], 200);
    }

}
