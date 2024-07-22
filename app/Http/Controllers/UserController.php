<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Throwable;

class UserController extends Controller
{

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

    public function store(Request $request)
    {
        //
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
                'message' => 'User créé.',
                'user' => $user
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'error' => 'Une erreur est survenue: ' . $e->getMessage(),
            ], 500);
        }
        
    }

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


    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            
            return response()->json([
                'message' => 'User supprimé avec succès.',
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'error' => 'Une erreur est survenue: ' . $e->getMessage(),
            ], 500);
        }
    }

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
                'message' => 'User mis à jour avec succès.',
                'user' => $user
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'error' => 'Une erreur est survenue: ' . $e->getMessage(),
            ], 500);
        }
    }
}
