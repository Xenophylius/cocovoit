<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Http\Request;
use Throwable;

class TripController extends Controller
{
        /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $trips = Trip::all();
            return response()->json($trips);
        } catch (Throwable $e) {
            return response()->json([
                'error' => 'Une erreur est survenue: ' . $e->getMessage(),
            ], 500);
        }
    }
 
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }
 
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'starting_point' => 'required|string|max:255',
            'ending_point' => 'required|string|max:255',
            'starting_at' => 'required|string|max:255',
            'available_places' => 'required|integer',
            'price' => 'required|integer',
            'user_id' => 'required|integer',
        ]);
 
        try {
            $trip = Trip::create($validated);
            return response()->json([
                'message' => 'Trip créé.',
                'trip' => $trip
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'error' => 'Une erreur est survenue.' . $e,
            ]);
        };

        
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $trip = Trip::findOrFail($id);
            return response()->json($trip, 200);
        } catch (Throwable $e) {
            return response()->json([
                'error' => 'Une erreur est survenue: ' . $e->getMessage(),
            ], 500);
        }
    }
 
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Trip $trip)
    {
        //
    }
 
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'starting_point' => 'sometimes|string|max:255',
            'ending_point' => 'sometimes|string|max:255',
            'starting_at' => 'sometimes|string|max:255',
            'available_places' => 'sometimes|string|max:255',
            'price' => 'sometimes|integer',
        ]);

        try {
            $trip = Trip::findOrFail($id);           
            $trip->update($validated);

            return response()->json([
                'message' => 'Trip mis à jour avec succès.',
                'user' => $trip
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'error' => 'Une erreur est survenue: ' . $e->getMessage(),
            ], 500);
        }
    }
 
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $trip = Trip::findOrFail($id);
            $trip->delete();
            
            return response()->json([
                'message' => 'Trip supprimé avec succès.',
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'error' => 'Une erreur est survenue: ' . $e->getMessage(),
            ], 500);
        }
    }
}
