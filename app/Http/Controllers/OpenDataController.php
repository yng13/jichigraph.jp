<?php

namespace App\Http\Controllers;

use App\Models\OpenData;
use Illuminate\Http\Request;

class OpenDataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = OpenData::query();

        // data_typeによる絞り込み
        if ($request->has('data_type')) {
            $query->where('data_type', $request->data_type);
        }

        // dataset_idによる絞り込み
        if ($request->has('dataset_id')) {
            $query->where('dataset_id', $request->dataset_id);
        }

        $openData = $query->get();

        return response()->json($openData);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $openData = OpenData::findOrFail($id);

        return response()->json($openData);
    }
}
