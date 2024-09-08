<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'last_name' => 'required|string',
            'first_name' => 'required|string',
            'middle_name' => 'required|string',
            'date_of_birth' => 'required|date',
            'sex' => 'required|string',
            'full_address' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {

            DB::beginTransaction();

            $user =  DB::table('users')->insertGetId([
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);

            $profile = DB::table('profiles')->insert([
                'user_id' => $user,
                'last_name' => $request->last_name,
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'date_of_birth' => date("Y-m-d", strtotime($request->date_of_birth)),
                'sex' => $request->sex,
                'full_address' => $request->full_address,
            ]);


            DB::commit();

            return response()->json([
                'message' => 'success',
                'data' => $profile,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage(),
            ], 409);
        }
    }

    public function login(Request $request)
    {

        try {
            DB::beginTransaction();


            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }


            $credentials = $request->only('email', 'password');

            if (!Auth::guard('web')->attempt($credentials)) {
                return response()->json([
                    'message' => 'Unauthorized'
                ], 401);
            }

            // Retrieve the authenticated user
            $user = Auth::guard('web')->user();

            $token = $user->createToken('@@@@@')->plainTextToken;

            DB::commit();

            return response()->json([
                'serviceToken' => $token,
                'token_type' => 'Bearer',
                'data' => $user,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage(),
            ], 409);
        }
    }

    public function logout(Request $request)
    {
        // $this->guard()->logout();
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'logged out']);
    }

    // public function resetPassword(Request $request)
    // {
    //
    //     $uid = Auth::user()->user_id;
    //
    //     $request->validate([
    //         'currentPassword' => 'required|string',
    //         'password' => 'required|string',
    //     ]);
    //
    //     try {
    //         DB::beginTransaction();
    //
    //         $userInfo = DB::table('users')
    //             ->where("user_id", $uid)
    //             ->first();
    //
    //         if (!Hash::check($request->currentPassword, $userInfo->password)) {
    //             // Wrong one
    //             return response()->json([
    //                 'message' => 'wrong current password ',
    //                 'data' => [],
    //             ], 201);
    //         }
    //
    //         DB::table('users')
    //             ->where('user_id', $uid)
    //             ->update(['password' => bcrypt($request->password)]);
    //
    //
    //         DB::commit();
    //
    //         return response()->json([
    //             'message' => 'success',
    //             'data' => $userInfo,
    //         ], 200);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return response()->json([
    //             'message' => $e->getMessage(),
    //         ], 409);
    //     }
    // }
}
