<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



/**
 * @OA\Schema(
 *     schema="Category",
 *     title="Category Model",
 *     description="Category model data",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Music"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class Category extends Model
{
    use HasFactory;

    // Define which fields can be mass assigned
    protected $fillable = [
        'name',
        'description'
    ];

    // Define the many-to-many relationship with Event
    public function events()
    {
        return $this->belongsToMany(Event::class);
    }
}
