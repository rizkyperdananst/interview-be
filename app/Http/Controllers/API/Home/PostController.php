<?php

namespace App\Http\Controllers\API\Home;

use App\Models\Post;
use App\Models\PostImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with('images', 'likes', 'comments')->get();

        return new PostResource(true, 'get all posts', $posts);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required',
            'image.*' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'user_id' => 'required|integer|exists:users,id',
            'caption' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $post = Post::create([
            'user_id' => $request->user_id,
            'caption' => $request->caption,
        ]);

        foreach ($request->file('image') as $image) {
            $imageName = $image->hashName();

            $image->storeAs('posts', $imageName, 'public');

            PostImage::create([
                'post_id' => $post->id,
                'image' => $imageName,
            ]);
        }

        return new PostResource(true, 'Post created successfully!', $post);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::with('images')->find($id);

        //return single post as a resource
        return new PostResource(true, 'Detail Data Post!', $post);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'nullable',
            'image.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'user_id' => 'required|integer|exists:users,id',
            'caption' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $post = Post::find($id);

        $post->update([
            'user_id' => $request->user_id,
            'caption' => $request->caption,
        ]);

        $post_image = Post::find($id);

        //check if image is not empty
        if ($request->hasFile('image')) {
            // $imageOld = 'storage/post/' . $post_image->image;
            

            foreach ($request->file('image') as $image) {
                // if (File::exists($imageOld)) {
                //     File::delete($imageOld);
                    $imageName = $image->hashName();

                    $image->storeAs('posts', $imageName, 'public');

                    Storage::delete('public/storage/posts/' . basename($post_image->image));

                    $post_image->update([
                        'post_id' => $post->id,
                        'image' => $imageName,
                    ]);
                }
            // }
        }

        return new PostResource(true, 'Post updated successfully!', $post);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::find($id);
        $post_images = PostImage::where('post_id', $post->id)->get();
        foreach ($post_images as $pImage) {
            $pImage->delete();
        }
        $post->delete();

        return new PostResource(true, 'Post deleted successfully!', []);
    }
}
