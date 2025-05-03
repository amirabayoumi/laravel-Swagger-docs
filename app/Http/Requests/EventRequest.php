<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="EventRequest",
 *     title="Event Request",
 *     description="Event request body data",
 *     required={"title", "start_date"},
 *     @OA\Property(property="title", type="string", example="Taste of Cultures"),
 *     @OA\Property(property="description", type="string", example="International food and culture festival celebrating global diversity in Brussels."),
 *     @OA\Property(property="location", type="string", example="Kaaistudio’s, Brussels"),
 *     @OA\Property(property="start_date", type="string", format="date-time", example="2023-10-15T09:00:00"),
 *     @OA\Property(property="end_date", type="string", format="date-time", example="2023-10-17T18:00:00"),
 *     @OA\Property(property="organizer", type="string", example="CultuurConnect")
 * )
 */
class EventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'organizer' => 'nullable|string|max:255',
        ];
    }
}
