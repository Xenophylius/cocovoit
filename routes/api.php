<?php

use App\Http\Controllers\TripController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Http\Middleware\CheckAdmin;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('user', [UserController::class, 'store']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [UserController::class, 'index']);
    Route::get('user/{user}', [UserController::class, 'show']);
    Route::put('user/{user}', [UserController::class, 'update']);
    Route::delete('user/{user}', [UserController::class, 'destroy']);
});
    
    
Route::resource('trip', TripController::class)
    ->only(['index', 'store', 'update', 'destroy', 'show'])
    ->middleware(['auth:sanctum']);
    
Route::post('login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);  
    $user = User::where('email', $request->email)->first();
        
    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }
        
    return response()->json([
        'token' => $user->createToken('Token Name')->plainTextToken
    ]);
});

Route::get('/docs/api-docs.json', function () {
    return response()->file(storage_path('api-docs.json'));
});
