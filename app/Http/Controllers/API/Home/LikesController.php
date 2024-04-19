<?php

namespace App\Http\Controllers\API\Home;

use App\Models\Like;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\LikesResource;
use Illuminate\Support\Facades\Validator;

class LikesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $likes = Like::with('user')->get();

        return new LikesResource(true, 'get all likes', $likes);
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
            'post_id' => 'required|integer|exists:posts,id',
            'user_id' => 'required|integer|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $like = Like::create([
            'post_id' => $request->post_id,
            'user_id' => $request->user_id,
        ]);
        
        return new LikesResource(true, 'Like created successfully', $like);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $like = Like::find($id)->delete();

        return new LikesResource(true, 'Like deleted successfully', NULL);
    }
}
