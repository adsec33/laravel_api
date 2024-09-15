<?php

namespace App\Http\Controllers;

use App\Models\geolocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GeolocationController extends Controller
{
    // public function index()
    // {
    //     $profile =  DB::table('users')
    //         ->join('profiles', 'users.user_id', '=', 'profiles.user_id')
    //         ->get()->map(function ($row) {
    //             $row->password =  "";
    //             return  $row;
    //         });
    //
    //     return response()->json([
    //         'message' => 'success',
    //         'data' => $profile,
    //     ], 201);
    // }

    public function store(Request $request)
    {
        $user = $request->user('api');

        $validator = Validator::make($request->all(), [
            'ip' => 'required|ip|unique:geolocations,ip',
            'hostname' => 'required|string|unique:geolocations,hostname',
            'city' => 'required|string',
            'region' => 'required|string',
            'country' => 'required|string',
            'loc' => 'required|string',
            'org' => 'required|string',
            'postal' => 'required|numeric',
            'time_zone' => 'required|string',
            'readme' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }


        try {

            DB::beginTransaction();

            DB::table('geolocations')->insert([
                'user_id' => $user->user_id,
                'ip' => $request->ip,
                'hostname' => $request->hostname,
                'city' => $request->city,
                'region' => $request->region,
                'country' => $request->country,
                'loc' => $request->loc,
                'org' => $request->org,
                'postal' => $request->postal,
                'time_zone' => $request->time_zone,
                'readme' => $request->readme,
            ]);


            DB::commit();

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

    public function update(request $request, $id)
    {
        $user = $request->user('api');

        $validator = Validator::make($request->all(), [
            'ip' => 'required|ip|unique:geolocations,ip',
            'hostname' => 'required|string|unique:geolocations,hostname',
            'city' => 'required|string',
            'region' => 'required|string',
            'country' => 'required|string',
            'loc' => 'required|string',
            'org' => 'required|string',
            'postal' => 'required|numeric',
            'time_zone' => 'required|string',
            'readme' => 'nullable|url',
        ]);

        try {
            DB::beginTransaction();

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::table('geolocations')
                ->where(
                    [
                        ['user_id', $user->user_id],
                        ['geo_location_id', $id],
                    ]
                )->update(
                    [
                        "ip" => $request->ip,
                        "hostname" => $request->hostname,
                        "city" => $request->city,
                        "region" => $request->region,
                        "country" => $request->country,
                        "loc" => $request->loc,
                        "org" => $request->org,
                        "postal" => $request->postal,
                        "time_zone" => $request->time_zone,
                    ]
                );


            DB::commit();

            return response()->json([
                'message' => 'updated',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage(),
            ], 409);
        }
    }

    public function index()
    {
        $posts =  DB::table('geolocations')
            ->orderBy('geo_location_id', 'desc')
            ->get();

        return response()->json([
            'message' => 'success',
            'data' => $posts,
        ], 201);
    }

    public function search_geolocation($srch)
    {
        $posts =  DB::table('geolocations')
            ->where('ip', 'like', '%' . $srch . '%')
            ->orderBy('geo_location_id', 'desc')
            ->get();

        return response()->json([
            'message' => 'success',
            'data' => $posts,
        ], 201);
    }

    public function delete($id)
    {
        $gl =  DB::table('geolocations')
            ->whereIn('geo_location_id', json_decode($id))
            ->delete();

        return response()->json([
            'message' => 'Deleted',
            'data' => $gl,
        ], 201);
    }
}
