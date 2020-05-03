<?php


namespace App\Http\Controllers;


use App\Http\Requests\MovieRequest;
use App\Models\Country;
use App\Models\Genre;
use App\Models\Image;
use App\Models\Movie;
use Illuminate\Support\Str;

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

    public function add(MovieRequest $request) {
        $fileName = Str::random(20) . ' ' . $request->file('image')->getClientOriginalName();
        $request->file('image')->storeAs('/movies/posters/', $fileName);
        $image = new Image(['path' => '/movies/posters/' . $fileName]);
        $image->save();
        $movie = new Movie($request->all());
        $movie->image_id = $image->id;
        $movie->save();
        $genres = json_decode($request->get('genres'));
        $movie->genres()->attach($genres);
        return response()->json($request->all());
    }

    public function update(Movie $movie) {

    }

    public function delete(Movie $movie) {

    }

    /**
     * Return list of available genres & countries for movie
     */
    public function data() {
        return response()->json([
            'genres' => Genre::select('id', 'name')->get(),
            'countries' => Country::select('id', 'name')->get()
        ]);
    }

//    /**
//     * Return list of available genres & countries for movie
//     */
//    public function search($item) {
//        $movies = Movie::with(['genres', 'country', 'image'])
//            ->where('title', 'LIKE', "%{$item}%")
//            ->orWhere('description', 'LIKE', "%{$item}%")
//            ->get();
//        return response()->json($movies);
//    }
}
