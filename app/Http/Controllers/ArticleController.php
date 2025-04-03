<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Traits\Imageable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ArticleController extends Controller
{
    use Imageable;

    public function create(Request $request)
    {

        if (!$request->user() || $request->user()->type !== 'Admin') {
            return response()->json([
                'message' => __('messages.operation_failed'),
                'error' => 'messages.Unauthorized'
            ], 403);
        }
        $validator = Validator::make($request->all(), [
            'article_name' => 'required|string|max:255',
            'website_name' => 'required|string|max:255',
            'explain' => 'required|string',
            'url' => 'required|url',
            'category_id' => 'required|exists:categories,id',
            'images' => 'array',
            'images.*' => 'image|mimes:jpg,png,jpeg,gif',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => __('messages.operation_failed'),
                'errors' => $validator->errors()
            ], 422);
        }


        $article = Article::create($request->except('images'));

        if ($request->has('images')) {
            $this->ssave($request->file('images'), $article->id);
        }

        return response()->json([
            'message' => __('messages.operation_success'),
            'data' => $article,
        ], 201);
    }

//    public function articlesByCategory($categoryId)
//    {
//        $articles = Article::where('category_id', $categoryId)->with( 'image')
//                            ->paginate(10);
//
//        if ($articles->isEmpty()) {
//            return response()->json([
//                'message' => __('messages.not_found'),
//            ], 404);
//        }
//
//        return response()->json([
//            'message' => __('messages.operation_success'),
//            'data' => $articles,
//        ], 200);
//    }
    public function articlesByCategory($categoryId)
    {
        $articles = Article::where('category_id', $categoryId)->with('image')->paginate(10);

        if ($articles->isEmpty()) {
            return response()->json([
                'message' => __('messages.not_found'),
            ], 404);
        }

        $formattedArticles = $articles->map(function($article) {
            return [
                'id' => $article->id,
                'title' => $article->title,
                'article_name'=> $article->article_name,
                'website_name'=> $article->website_name,
                'explain'=> $article->explain,
                'url'=> $article->url,
                'category_id'=> $article->category_id,
                'image' => $article->image
            ];
        });

        return response()->json([
            'message' => __('messages.operation_success'),
            'data' => $formattedArticles,
            'next_page_url' => $articles->nextPageUrl(),
            'prev_page_url' => $articles->previousPageUrl(),
        ], 200);
    }

    public function show($id)
    {
        $article = Article::with( 'image')->find($id);

        if (!$article) {
            return response()->json([
                'message' => __('messages.not_found'),
            ], 404);
        }

        return response()->json([
            'message' => __('messages.operation_success'),
            'data' => $article,
        ], 200);
    }

    public function update(Request $request, $id)
    {

        if (!$request->user() || $request->user()->type !== 'Admin') {
            return response()->json([
                'message' => __('messages.operation_failed'),
                'error' => 'messages.Unauthorized'
            ], 403);
        }


        $article = Article::find($id);

        if (!$article) {
            return response()->json([
                'message' => __('messages.operation_failed'),
                'error' => __('messages.not_found')
            ], 404);
        }


        $validator = Validator::make($request->all(), [
            'article_name' => 'nullable|string|max:255',
            'website_name' => 'nullable|string|max:255',
            'explain' => 'nullable|string',
            'url' => 'nullable|url',
            'category_id' => 'nullable|exists:categories,id',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpg,png,jpeg,gif',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => __('messages.operation_failed'),
                'errors' => $validator->errors()
            ], 422);
        }


        $article->update($request->except('images'));


        if ($request->has('images')) {
            $this->ssave($request->file('images'), $article->id);
        }

        return response()->json([
            'message' => __('messages.operation_success'),
            'data' => $article,
        ], 200);
    }



    public function destroyImage(Request $request, $id)
    {

        if (!$request->user() || $request->user()->type !== 'Admin') {
            return response()->json([
                'message' => __('messages.operation_failed'),
                'error' => 'messages.Unauthorized'
            ], 403);
        }

        $image = Image::find($id);

        if (!$image) {
            return response()->json(['message' => __('messages.not_found')], 404);
        }
        $filePath = public_path('articles/' . basename($image->path));

        Log::info("Attempting to delete file: " . $filePath);

        if (File::exists($filePath)) {
            if (File::delete($filePath)) {
                Log::info("Successfully deleted: " . $filePath);
            } else {
                Log::warning("Failed to delete: " . $filePath);
            }
        } else {
            Log::warning("File not found for deletion: " . $filePath);
        }


        $image->delete();

        return response()->json(['message' => __('messages.delete_success')], 200);
    }

    public function searchByWebsiteName(Request $request)
    {
        $request->validate([
            'website_name' => 'required|string|max:255',
        ]);

        $websiteName = $request->input('website_name');

        $articles = Article::where('website_name', 'like', '%' . $websiteName . '%')
                           ->paginate(10);

        if ($articles->isEmpty()) {
            return response()->json(['message' => __('messages.not_found')], 404);
        }

        return response()->json([
            'message' => __('messages.operation_success'),
            'data' => $articles,
        ], 200);
    }

    public function destroy(Request $request, $id = null)
    {
        if (is_null($id)) {
            return response()->json(['message' => __('messages.not_found')], 404);
        }

        if (!$request->user() || $request->user()->type !== 'Admin') {
            return response()->json(['message' => __('messages.Unauthorized')], 403);
        }

        $article = Article::find($id);
        if (!$article) {
            return response()->json(['message' => __('messages.not_found')], 404);
        }

        foreach ($article->image as $image) {
            $filePath = public_path('articles/' . basename($image->path));

            \Log::info("Attempting to delete file: " . $filePath);

            if (File::exists($filePath)) {
                if (File::delete($filePath)) {
                    \Log::info("Successfully deleted: " . $filePath);
                } else {
                    \Log::warning("Failed to delete: " . $filePath);
                }
            } else {
                \Log::warning("File not found for deletion: " . $filePath);
            }

            $image->delete();
        }

        $article->delete();
        return response()->json(['message' => __('messages.delete_success')], 200);
    }

}
