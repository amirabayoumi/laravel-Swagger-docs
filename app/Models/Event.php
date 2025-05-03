<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Event",
 *     title="Event Model",
 *     description="Event model data",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Taste of Cultures"),
 *     @OA\Property(property="description", type="string", example="International food and culture festival celebrating global diversity in Brussels."),
 *     @OA\Property(property="location", type="string", example="Kaaistudio's, Brussels"),
 *     @OA\Property(property="start_date", type="string", format="date-time", example="2023-10-15T09:00:00"),
 *     @OA\Property(property="end_date", type="string", format="date-time", example="2023-10-17T18:00:00"),
 *     @OA\Property(property="organizer", type="string", example="CultuurConnect"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'location',
        'start_date',
        'end_date',
        'organizer',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];
}
