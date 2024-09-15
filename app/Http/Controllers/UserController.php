<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{


    public function login(Request $request)
    {

        $geoloc = Http::get('https://ipinfo.io/json');


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
                    'message' => 'Unauthorized wrong Email / Password'
                ], 401);
            }

            // Retrieve the authenticated user
            $user = Auth::guard('web')->user();

            $token = $user->createToken('@@@@@')->plainTextToken;


            if ($geoloc->successful()) {

                $gl =  DB::table('geolocations')
                    ->where("ip", $geoloc->json()["ip"])
                    ->get();

                if (count($gl) === 0) {
                    DB::table('geolocations')->insert([
                        'user_id' => $user->user_id,
                        'ip' => $geoloc->json()["ip"],
                        'hostname' => $geoloc->json()["hostname"],
                        'city' => $geoloc->json()["city"],
                        'region' => $geoloc->json()["region"],
                        'country' => $geoloc->json()["country"],
                        'loc' => $geoloc->json()["loc"],
                        'org' => $geoloc->json()["org"],
                        'postal' => $geoloc->json()["postal"],
                        'time_zone' => $geoloc->json()["timezone"],
                        'readme' => $geoloc->json()["readme"],
                    ]);
                }
            }

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
}
