<?php

namespace App\Http\Controllers;

use App\Models\Dataset;
use Illuminate\Http\Request;

class DatasetsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $datasets = Dataset::all();

        return response()->json($datasets);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $dataset = Dataset::findOrFail($id);

        return response()->json($dataset);
    }

    /**
     * Get the open data records for the dataset.
     */
    public function getOpenData(string $id)
    {
        $dataset = Dataset::findOrFail($id);

        $openData = $dataset->openData;

        return response()->json($openData);
    }
}
