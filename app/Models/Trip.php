<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @OA\Schema(
 *     schema="Trip",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="starting_point", type="string", example="Paris"),
 *     @OA\Property(property="ending_point", type="string", example="Londres"),
 *     @OA\Property(property="starting_at", type="string", format="date-time", example="2024-08-01T10:00:00Z"),
 *     @OA\Property(property="available_places", type="integer", example=10),
 *     @OA\Property(property="price", type="integer", example=100),
 *     @OA\Property(property="user_id", type="integer", example=1),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-07-23T12:34:56Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-07-23T12:34:56Z")
 * )
 */

 /**
 * @OA\Schema(
 *     schema="Trip",
 *     type="object",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="starting_point",
 *         type="string",
 *         example="Paris"
 *     ),
 *     @OA\Property(
 *         property="ending_point",
 *         type="string",
 *         example="Lyon"
 *     ),
 *     @OA\Property(
 *         property="date",
 *         type="string",
 *         format="date",
 *         example="2024-08-01"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         example="2024-07-29T12:34:56Z"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         example="2024-07-29T12:34:56Z"
 *     )
 * )
 */
class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'starting_point',
        'ending_point',
        'starting_at',
        'available_places',
        'price',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id'); // Assurez-vous que 'user_id' est la clé étrangère correcte
    }

    public function ratings()
{
    return $this->hasMany(Rating::class);
}
}
