<?php


namespace App\Http\Controllers;


use App\Models\Movie;

class MovieController extends Controller
{
    public function list() {
        $movies = Movie::with(['genres', 'country', 'image'])->get();
        $movies->transform(function ($item, $key) {
            $genres = $item->genres->map( function ($item, $key) {
                return $item->name;
            });
            return [
                'id' => $item->id,
                'title' => $item->title,
                'genres' => $genres,
                'poster_path' => $item->image->path,
                'description' => $item->description,
                'country' => $item->country->name
            ];
        });
        return response()->json($movies);
    }
}
