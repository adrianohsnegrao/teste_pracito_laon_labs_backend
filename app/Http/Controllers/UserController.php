<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::with('profile', 'plan')->get();
        return response()->json($users, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), ['name' => 'required|string|max:255', 'email' => 'required|string|email|max:255|unique:users', 'password' => 'required|string|min:8|confirmed',]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();
        return response()->json($user, 201);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $validator = Validator::make($request->all(), ['name' => 'sometimes|string|max:255', 'email' => 'sometimes|string|email|max:255|unique:users,email,' . $id, 'password' => 'sometimes|string|min:8|confirmed',]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $user->name = $request->name ?? $user->name;
        $user->email = $request->email ?? $user->email;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->save();
        return response()->json($user, 200);
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $user->delete();
        return response()->json(null, 204);
    }
}
