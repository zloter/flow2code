<?php

namespace App\Http\Requests;

use App\Models\Genre;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMovieRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => '',
            'genres' => ['', 'json', function ($attribute, $value, $fail) {
                $genres = json_decode($value);
                $length = sizeof($genres);
                if ($length < 1) {
                    $fail(__('There must be at least one genre'));
                } else {
                    $q = Genre::whereId($genres[0]);
                    for ($i = 1; $i < $length; $i ++) {
                        $q = $q->orWhere('id', '=', $genres[$i]);
                    }
                    if ($q->count() < sizeof($genres)) {
                        $fail(__('Genres must be send as a list of id\'s of existing genres'));
                    }
                }
            }],
            'image' => 'image',
            'description' => '',
            'country_id' => 'exists:countries,id',
        ];
    }
}
