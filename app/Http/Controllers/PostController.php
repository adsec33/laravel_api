<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function store(Request $request)
    {
        $user = $request->user('api');

        $validator = Validator::make($request->all(), [
            'post' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {

            DB::beginTransaction();

            $post =  DB::table('posts')->insert([
                'user_id' => $user->user_id,
                'post' => $request->post,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'success',
                'data' => $post,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage(),
            ], 409);
        }
    }

    public function index()
    {
        $posts =  DB::table('posts')
            ->orderBy('post_id', 'desc')
            ->get();

        return response()->json([
            'message' => 'success',
            'data' => $posts,
        ], 201);
    }

    public function delete($id)
    {
        $posts =  DB::table('posts')
            ->where('post_id', $id)
            ->delete();

        return response()->json([
            'message' => 'deleted',
            'data' => $posts,
        ], 201);
    }
}
