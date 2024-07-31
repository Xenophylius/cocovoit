<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;
use OpenApi\Annotations as OA;

class RatingController extends Controller
{
    /**
     * @OA\Post(
     *     path="/rating",
     *     summary="Noter un voyage",
     *     tags={"Notation"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"trip_id", "rating"},
     *             @OA\Property(property="trip_id", type="integer", example=1),
     *             @OA\Property(property="rating", type="integer", example=5, description="Note entre 1 et 5")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Notation ajoutée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Notation ajoutée avec succès."),
     *             @OA\Property(property="rating", ref="#/components/schemas/Rating")
     *         )
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
        $validator = Validator::make($request->all(), [
            'trip_id' => 'required|integer|exists:trips,id',
            'rating' => 'required|integer|between:1,5',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        try {
            $rating = Rating::updateOrCreate(
                ['user_id' => auth()->id(), 'trip_id' => $request->trip_id],
                ['rating' => $request->rating]
            );

            return response()->json([
                'message' => 'Notation ajoutée avec succès.',
                'rating' => $rating
            ], 201);
        } catch (Throwable $e) {
            return response()->json([
                'error' => 'Une erreur est survenue: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/rating/{trip_id}",
     *     summary="Obtenir les notations pour un voyage",
     *     tags={"Notation"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="trip_id",
     *         in="path",
     *         required=true,
     *         description="ID du voyage",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Notations récupérées avec succès",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Rating")
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
    public function index($trip_id)
    {
        try {
            $ratings = Rating::where('trip_id', $trip_id)->get();
            return response()->json($ratings, 200);
        } catch (Throwable $e) {
            return response()->json([
                'error' => 'Une erreur est survenue: ' . $e->getMessage(),
            ], 500);
        }
    }
}
