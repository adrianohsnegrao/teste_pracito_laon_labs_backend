<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $profiles = Profile::with('user')->get();
        return response()->json($profiles, 200);
    }

    public function show($id)
    {
        $profile = Profile::with('user')->find($id);
        if (!$profile) {
            return response()->json(['message' => 'Profile not found'], 404);
        }
        return response()->json($profile, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), ['user_id' => 'required|exists:users,id', 'name' => 'required|string|max:255', 'birthdate' => 'sometimes|date', 'avatar' => 'sometimes|string|max:255',]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $profile = new Profile();
        $profile->user_id = $request->user_id;
        $profile->name = $request->name;
        $profile->birthdate = $request->birthdate;
        $profile->avatar = $request->avatar;
        $profile->save();
        return response()->json($profile, 201);
    }

    public function update(Request $request, $id)
    {
        $profile = Profile::find($id);
        if (!$profile) {
            return response()->json(['message' => 'Profile not found'], 404);
        }
        $validator = Validator::make($request->all(), ['name' => 'sometimes|string|max:255', 'birthdate' => 'sometimes|date', 'avatar' => 'sometimes|string|max:255',]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $profile->name = $request->name ?? $profile->name;
        $profile->birthdate = $request->birthdate ?? $profile->birthdate;
        $profile->avatar = $request->avatar ?? $profile->avatar;
        $profile->save();
        return response()->json($profile, 200);
    }

    public function destroy($id)
    {
        $profile = Profile::find($id);
        if (!$profile) {
            return response()->json(['message' => 'Profile not found'], 404);
        }
        $profile->delete();
        return response()->json(null, 204);
    }
}
