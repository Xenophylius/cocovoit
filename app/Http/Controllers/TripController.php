<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Http\Request;
use Throwable;
use OpenApi\Annotations as OA;

class TripController extends Controller
{
    /**
     * @OA\Get(
     *     path="/trip",
     *     summary="Obtenir la liste des voyages",
     *     tags={"Voyage"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des voyages récupérée avec succès",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Trip")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur serveur interne"
     *     )
     * )
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
     * @OA\Post(
     *     path="/trip",
     *     summary="Créer un nouveau voyage",
     *     tags={"Voyage"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"starting_point", "ending_point", "starting_at", "available_places", "price", "user_id"},
     *             @OA\Property(property="starting_point", type="string", example="Paris"),
     *             @OA\Property(property="ending_point", type="string", example="Londres"),
     *             @OA\Property(property="starting_at", type="string", example="2024-08-01T10:00:00Z"),
     *             @OA\Property(property="available_places", type="integer", example=10),
     *             @OA\Property(property="price", type="integer", example=100),
     *             @OA\Property(property="user_id", type="integer", example=1)
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Voyage créé avec succès",
     *         @OA\JsonContent(ref="#/components/schemas/Trip")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Requête incorrecte"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur serveur interne"
     *     )
     * )
     */
    public function store(Request $request)
    {
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
                'message' => 'Voyage créé.',
                'trip' => $trip
            ], 201);
        } catch (Throwable $e) {
            return response()->json([
                'error' => 'Une erreur est survenue: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/trip/{id}",
     *     summary="Obtenir un voyage par ID",
     *     tags={"Voyage"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du voyage",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Voyage récupéré avec succès",
     *         @OA\JsonContent(ref="#/components/schemas/Trip")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Voyage non trouvé"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur serveur interne"
     *     )
     * )
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
     * @OA\Put(
     *     path="/trip/{id}",
     *     summary="Mettre à jour un voyage",
     *     tags={"Voyage"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du voyage",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="starting_point", type="string", example="Paris"),
     *             @OA\Property(property="ending_point", type="string", example="Londres"),
     *             @OA\Property(property="starting_at", type="string", example="2024-08-01T10:00:00Z"),
     *             @OA\Property(property="available_places", type="integer", example=10),
     *             @OA\Property(property="price", type="integer", example=100)
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Voyage mis à jour avec succès",
     *         @OA\JsonContent(ref="#/components/schemas/Trip")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Requête incorrecte"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Voyage non trouvé"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur serveur interne"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'starting_point' => 'sometimes|string|max:255',
            'ending_point' => 'sometimes|string|max:255',
            'starting_at' => 'sometimes|string|max:255',
            'available_places' => 'sometimes|integer',
            'price' => 'sometimes|integer',
        ]);

        try {
            $trip = Trip::findOrFail($id);
            $trip->update($validated);

            return response()->json([
                'message' => 'Voyage mis à jour avec succès.',
                'trip' => $trip
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'error' => 'Une erreur est survenue: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/trip/{id}",
     *     summary="Supprimer un voyage",
     *     tags={"Voyage"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du voyage",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Voyage supprimé avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Voyage supprimé avec succès.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Voyage non trouvé"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur serveur interne"
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            $trip = Trip::findOrFail($id);
            $trip->delete();

            return response()->json([
                'message' => 'Voyage supprimé avec succès.',
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'error' => 'Une erreur est survenue: ' . $e->getMessage(),
            ], 500);
        }
    }
}
