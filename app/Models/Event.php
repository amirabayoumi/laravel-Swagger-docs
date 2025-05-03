<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Event",
 *     title="Event",
 *     description="Event model",
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="title", type="string", example="Conference"),
 *     @OA\Property(property="description", type="string", example="Annual tech conference"),
 *     @OA\Property(property="location", type="string", example="Convention Center"),
 *     @OA\Property(property="start_date", type="string", format="date-time"),
 *     @OA\Property(property="end_date", type="string", format="date-time"),
 *     @OA\Property(property="organizer", type="string", example="Tech Company Ltd"),
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
