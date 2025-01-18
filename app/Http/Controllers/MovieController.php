<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->plan->name === 'premium') {
            $movies = Movie::all();
        } else {
            $movies = Movie::where('genre', '!=', 'exclusive')->get();
        }

        return response()->json($movies, 200);
    }
    public function show($id)
    {
        $movie = Movie::find($id);
        if (!$movie) {
            return response()->json(['message' => 'Movie not found'], 404);
        }
        return response()->json($movie, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), ['title' => 'required|string|max:255', 'description' => 'required|string', 'genre' => 'required|string|max:50', 'duration' => 'required|integer',]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $movie = new Movie();
        $movie->title = $request->title;
        $movie->description = $request->description;
        $movie->genre = $request->genre;
        $movie->duration = $request->duration;
        $movie->save();
        return response()->json($movie, 201);
    }

    public function update(Request $request, $id)
    {
        $movie = Movie::find($id);
        if (!$movie) {
            return response()->json(['message' => 'Movie not found'], 404);
        }
        $validator = Validator::make($request->all(), ['title' => 'sometimes|string|max:255', 'description' => 'sometimes|string', 'genre' => 'sometimes|string|max:50', 'duration' => 'sometimes|integer',]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $movie->title = $request->title ?? $movie->title;
        $movie->description = $request->description ?? $movie->description;
        $movie->genre = $request->genre ?? $movie->genre;
        $movie->duration = $request->duration ?? $movie->duration;
        $movie->save();
        return response()->json($movie, 200);
    }

    public function destroy($id)
    {
        $movie = Movie::find($id);
        if (!$movie) {
            return response()->json(['message' => 'Movie not found'], 404);
        }
        $movie->delete();
        return response()->json(null, 204);
    }
}
