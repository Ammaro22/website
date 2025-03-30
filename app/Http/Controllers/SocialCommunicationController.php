<?php

namespace App\Http\Controllers;

use App\Models\Social_communication;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SocialCommunicationController extends Controller
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
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        $socialCommunication = new Social_communication();
        $socialCommunication->name = $validatedData['name'];
        $socialCommunication->address = $validatedData['address'];
        $socialCommunication->save();

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
        $socialCommunication = Social_communication::find($id);
        if (!$socialCommunication) {
            return response()->json([
                'message' => __('messages.operation_failed'),
                'error' => 'messages.NotFound'
            ], 404);
        }

        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'address' => 'sometimes|required|string|max:255',
        ]);

        $socialCommunication->fill($validatedData);
        $socialCommunication->save();

        return response()->json([
            'message' => __('messages.operation_success'),
        ], 200);
    }

    public function show()
    {

        $socialCommunication = Social_communication::all();

        if (!$socialCommunication) {
            return response()->json([
                'message' => __('messages.operation_failed'),
                'error' => 'messages.NotFound'
            ], 404);
        }

        return response()->json([
            'message' => __('messages.operation_success'),
            'data' => $socialCommunication,
        ], 200);
    }
}
