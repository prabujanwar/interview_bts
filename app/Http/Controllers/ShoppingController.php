<?php

namespace App\Http\Controllers;

use App\Shopping;
use Illuminate\Http\Request;

class ShoppingController extends Controller
{
    public function fetch(Request $request, $id = null)
    {

        if ($id) {
            $fetch = Shopping::where('id', $id)->first();
        } else {
            $fetch = Shopping::all();
        }

        return response()->json([
            'data' => $fetch,
            'message' => "fetch data",
        ]);
    }

    public function create(Request $request)
    {
        $create = Shopping::create($request->all());

        return response()->json([
            'data' => $create,
            'message' => "created",
        ]);
    }

    public function update(Request $request, $id)
    {
        $update = Shopping::where('id', $request->id)->update([
            'name' => $request->name,
            'createddate' => $request->createddate,
        ]);

        return response()->json([
            'data' => $update,
            'message' => "updated",
        ]);
    }

    public function delete(Request $request, $id)
    {
        $create = Shopping::where('id', $id)->delete();

        return response()->json([
            'data' => $create,
            'message' => "deleted",
        ]);
    }
}
