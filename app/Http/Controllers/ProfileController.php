<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        $profile =  DB::table('users')
            ->join('profiles', 'users.user_id', '=', 'profiles.user_id')
            ->get()->map(function ($row) {
                $row->password =  "";
                return  $row;
            });

        return response()->json([
            'message' => 'success',
            'data' => $profile,
        ], 201);
    }
}
