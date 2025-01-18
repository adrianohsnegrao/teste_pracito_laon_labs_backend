<?php

namespace App\Http\Controllers;

use App\Models\Series;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SeriesController extends Controller
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
            $series = Series::all();
        } else {
            $series = Series::where('genre', '!=', 'exclusive')->get();
        }

        return response()->json($series, 200);
    }

    public function show($id)
    {
        $series = Series::find($id);
        if (!$series) {
            return response()->json(['message' => 'Series not found'], 404);
        }
        return response()->json($series, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), ['title' => 'required|string|max:255', 'description' => 'required|string', 'genre' => 'required|string|max:50', 'seasons' => 'required|integer',]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $series = new Series();
        $series->title = $request->title;
        $series->description = $request->description;
        $series->genre = $request->genre;
        $series->seasons = $request->seasons;
        $series->save();
        return response()->json($series, 201);
    }

    public function update(Request $request, $id)
    {
        $series = Series::find($id);
        if (!$series) {
            return response()->json(['message' => 'Series not found'], 404);
        }
        $validator = Validator::make($request->all(), ['title' => 'sometimes|string|max:255', 'description' => 'sometimes|string', 'genre' => 'sometimes|string|max:50', 'seasons' => 'sometimes|integer',]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $series->title = $request->title ?? $series->title;
        $series->description = $request->description ?? $series->description;
        $series->genre = $request->genre ?? $series->genre;
        $series->seasons = $request->seasons ?? $series->seasons;
        $series->save();
        return response()->json($series, 200);
    }

    public function destroy($id)
    {
        $series = Series::find($id);
        if (!$series) {
            return response()->json(['message' => 'Series not found'], 404);
        }
        $series->delete();
        return response()->json(null, 204);
    }
}
