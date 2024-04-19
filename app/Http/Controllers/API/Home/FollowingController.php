<?php

namespace App\Http\Controllers\API\Home;

use App\Models\Following;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\FollowingResource;
use Illuminate\Support\Facades\Validator;

class FollowingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $followings = Following::with('user')->get();

        return new FollowingResource(true, 'Get all following', $followings);
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
            'following_user_id' => 'required|integer|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $following = Following::create([
            'user_id' => $request->user_id,
            'following_user_id' => $request->following_user_id,
        ]);
        
        return new FollowingResource(true, 'Following created successfully', $following);
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
        $following = Following::find($id)->delete();

        return new FollowingResource(true, 'Following deleted successfully', NULL);
    }
}
