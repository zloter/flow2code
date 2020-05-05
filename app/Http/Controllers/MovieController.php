<?php


namespace App\Http\Controllers;


use App\Http\Requests\MovieRequest;
use App\Models\Country;
use App\Models\Genre;
use App\Models\Image;
use App\Models\Movie;
use App\Services\FileService;

class MovieController extends Controller
{
    private $fileService;

    public function __construct(FileService $fileService) {
        $this->fileService = $fileService;
    }

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
                'poster_path' => config('movies.front_prefix_path') . $item->image->path,
                'description' => $item->description,
                'country' => $item->country->name
            ];
        });
        return response()->json($movies);
    }

    public function add(MovieRequest $request) {
        $path = $this->fileService->storeImage($request->file('image'));
        $image = new Image(['path' => $path]);
        $image->save();
        $movie = new Movie($request->all());
        $movie->image_id = $image->id;
        $movie->save();
        $genres = json_decode($request->get('genres'));
        $movie->genres()->attach($genres);
        return response()->json(['success' => __('Sucess!')]);
    }

    public function update(Movie $movie, MovieRequest $request) {
        if ($request->hasFile('image')) {
            $image = $movie->image()->first();
            $this->fileService->removeFile($image->path);
            $path = $this->fileService->storeImage($request->file('image'));
            $image->path = $path;
            $image->save();
        }
        if ($request->get('genres')) {
            $genres = json_decode($request->get('genres'));
            $movie->genres()->sync($genres);
        }
        $movie->update($request->all());
        return response()->json(['success' => __('Sucess!')]);
    }

    public function delete(Movie $movie) {
        $image = $movie->image()->first();
        $this->fileService->removeFile($image->path);
        $movie->delete();
        $image->delete();
        return response()->json(['success' => __('Sucess!')]);
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
