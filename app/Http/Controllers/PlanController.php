<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Plan::all(), 200);
    }

    public function show($id)
    {
        $plan = Plan::find($id);
        if (!$plan) {
            return response()->json(['message' => 'Plan not found'], 404);
        }
        return response()->json($plan, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), ['name' => 'required|string|max:255', 'price' => 'required|numeric', 'description' => 'sometimes|string',]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $plan = new Plan();
        $plan->name = $request->name;
        $plan->price = $request->price;
        $plan->description = $request->description;
        $plan->save();
        return response()->json($plan, 201);
    }

    public function update(Request $request, $id)
    {
        $plan = Plan::find($id);
        if (!$plan) {
            return response()->json(['message' => 'Plan not found'], 404);
        }
        $validator = Validator::make($request->all(), ['name' => 'sometimes|string|max:255', 'price' => 'sometimes|numeric', 'description' => 'sometimes|string',]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $plan->name = $request->name ?? $plan->name;
        $plan->price = $request->price ?? $plan->price;
        $plan->description = $request->description ?? $plan->description;
        $plan->save();
        return response()->json($plan, 200);
    }

    public function destroy($id)
    {
        $plan = Plan::find($id);
        if (!$plan) {
            return response()->json(['message' => 'Plan not found'], 404);
        }
        $plan->delete();
        return response()->json(null, 204);
    }
}
