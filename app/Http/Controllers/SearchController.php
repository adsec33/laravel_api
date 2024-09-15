<?php

namespace App\Http\Controllers;

use App\Models\search;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SearchController extends Controller
{
    public function index()
    {
        $srch =  DB::table('searches')
            ->limit(5)
            ->orderBy('search_id', 'desc')
            ->get();

        return response()->json([
            'message' => 'success',
            'data' => $srch,
        ], 201);
    }

    public function store(Request $request)
    {
        $user = $request->user('api');

        $validator = Validator::make($request->all(), [
            'searches' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }


        try {

            DB::beginTransaction();

            // $srch =  DB::table('searches')
            //     ->where("search", $request->searches)
            //     ->get();
            // if (count($srch) == 0) {
            DB::table('searches')->insert([
                'user_id' => $user->user_id,
                'search' => $request->searches,
            ]);
            DB::commit();
            // }
            return response()->json([
                'message' => 'success',
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage(),
            ], 409);
        }
    }
}
