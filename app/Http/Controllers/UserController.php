<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Throwable;
use OpenApi\Annotations as OA;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/user",
     *     summary="Obtenir la liste des utilisateurs",
     *     tags={"Utilisateur"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des utilisateurs récupérée avec succès",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/User")
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
            $users = User::all();
            return response()->json($users);
        } catch (Throwable $e) {
            return response()->json([
                'error' => 'Une erreur est survenue: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/user",
     *     summary="Créer un nouvel utilisateur",
     *     tags={"Utilisateur"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"lastname", "firstname", "email", "password"},
     *             @OA\Property(property="lastname", type="string", example="Doe"),
     *             @OA\Property(property="firstname", type="string", example="John"),
     *             @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="secret123")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Utilisateur créé avec succès",
     *         @OA\JsonContent(ref="#/components/schemas/User")
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
            'lastname' => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'password' => 'required|string|max:255',
        ]);

        try {
            $validated['password'] = bcrypt($validated['password']); // Hash password
            $user = User::create($validated);
            return response()->json([
                'message' => 'Utilisateur créé.',
                'user' => $user
            ], 201);
        } catch (Throwable $e) {
            return response()->json([
                'error' => 'Une erreur est survenue: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/user/{id}",
     *     summary="Obtenir un utilisateur par ID",
     *     tags={"Utilisateur"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de l'utilisateur",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Utilisateur récupéré avec succès",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Utilisateur non trouvé"
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
            $user = User::findOrFail($id);
            return response()->json($user, 200);
        } catch (Throwable $e) {
            return response()->json([
                'error' => 'Une erreur est survenue: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/user/{id}",
     *     summary="Mettre à jour un utilisateur",
     *     tags={"Utilisateur"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de l'utilisateur",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="lastname", type="string", example="Doe"),
     *             @OA\Property(property="firstname", type="string", example="John"),
     *             @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="secret123"),
     *             @OA\Property(property="role", type="string", example="admin"),
     *             @OA\Property(property="trips_id", type="integer", example=1),
     *             @OA\Property(property="avatar", type="string", example="avatar.png")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Utilisateur mis à jour avec succès",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Requête incorrecte"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Utilisateur non trouvé"
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
            'lastname' => 'sometimes|string|max:255',
            'firstname' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $id,
            'password' => 'sometimes|string|min:8',
            'role' => 'sometimes|string|max:255',
            'trips_id' => 'nullable|integer',
            'avatar' => 'nullable|string|max:255',
        ]);

        try {
            $user = User::findOrFail($id);

            if (isset($validated['password'])) {
                $validated['password'] = bcrypt($validated['password']); // Hash password
            }

            $user->update($validated);

            return response()->json([
                'message' => 'Utilisateur mis à jour avec succès.',
                'user' => $user
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'error' => 'Une erreur est survenue: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/user/{id}",
     *     summary="Supprimer un utilisateur",
     *     tags={"Utilisateur"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de l'utilisateur",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Utilisateur supprimé avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Utilisateur supprimé avec succès.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Utilisateur non trouvé"
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
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json([
                'message' => 'Utilisateur supprimé avec succès.',
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'error' => 'Une erreur est survenue: ' . $e->getMessage(),
            ], 500);
        }
    }
}
