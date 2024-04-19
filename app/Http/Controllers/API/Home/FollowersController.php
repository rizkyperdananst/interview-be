<?php

namespace App\Http\Controllers\API\Home;

use App\Models\Followers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\FollowersResource;
use Illuminate\Support\Facades\Validator;

class FollowersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $followers = Followers::with('user')->get();

        return new FollowersResource(true, 'get all followers', $followers);
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
            'user_id' => 'required|integer|exists:users,id',
            'followers_user_id' => 'required|integer|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $followers = Followers::create([
            'user_id' => $request->user_id,
            'followers_user_id' => $request->followers_user_id,
        ]);
        
        return new FollowersResource(true, 'Followers created successfully', $followers);
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
        $following = Followers::find($id)->delete();

        return new FollowersResource(true, 'Followers deleted successfully', NULL);
    }
}
