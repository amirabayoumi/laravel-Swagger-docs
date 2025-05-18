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
 *     @OA\Property(property="latitude", type="number", format="float", example=50.8503),
 *     @OA\Property(property="longitude", type="number", format="float", example=4.3517),
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
        'latitude',
        'longitude',
        'start_date',
        'end_date',
        'organizer',
    ];

    // Make sure latitude and longitude are NOT in the hidden array
    protected $hidden = [
        // 'latitude', // Remove or comment this line if it exists
        // 'longitude', // Remove or comment this line if it exists
    ];

    // Explicitly define visible attributes to include latitude and longitude
    protected $visible = [
        'id',
        'title',
        'description',
        'location',
        'latitude',
        'longitude',
        'start_date',
        'end_date',
        'organizer',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    // Make sure these fields are always included in API responses
    protected $appends = ['coordinates'];

    // Add a helper method to get formatted coordinates
    public function getCoordinatesAttribute()
    {
        if ($this->latitude && $this->longitude) {
            return [
                'latitude' => (float)$this->latitude,
                'longitude' => (float)$this->longitude
            ];
        }
        return null;
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_event')->withTimestamps();
    }
}
