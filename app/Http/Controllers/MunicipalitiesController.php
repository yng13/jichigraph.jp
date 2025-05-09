<?php

namespace App\Http\Controllers;

use App\Models\Municipality;
use Illuminate\Http\Request;

class MunicipalitiesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $municipalities = Municipality::all();

        return response()->json($municipalities);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $municipality = Municipality::findOrFail($id);

        return response()->json($municipality);
    }

    /**
     * Get the open data records for the municipality.
     */
    public function getOpenData(string $id)
    {
        $municipality = Municipality::findOrFail($id);

        $openData = $municipality->openData;

        return response()->json($openData);
    }
}
