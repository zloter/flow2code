<?php


namespace App\Http\Controllers;


use App\Http\Requests\AddMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Models\Country;
use App\Models\Genre;
use App\Models\Image;
use App\Models\Movie;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class MovieController extends Controller
{
    /**
     * @var FileService
     */
    private $fileService;

    /**
     * MovieController constructor.
     * @param FileService $fileService
     */
    public function __construct(FileService $fileService) {
        $this->fileService = $fileService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function list() {
        $movies = Movie::with(['genres', 'country', 'image'])->get();
        $movies = $this->transformMovieList($movies);
        return response()->json($movies);
    }

    /**
     * @param AddMovieRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(AddMovieRequest $request) {
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

    /**
     * @param Movie $movie
     * @param UpdateMovieRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Movie $movie, UpdateMovieRequest $request) {
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

    /**
     * @param Movie $movie
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
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

    /**
     * Return list of available genres & countries for movie
     */
    public function search(Request $request) {
        $item = $request->get('item');
        $movies = Movie::with(['genres', 'country', 'image'])
            ->where('title', 'LIKE', "%{$item}%")
            ->orWhere('description', 'LIKE', "%{$item}%")
            ->get();
        $movies = $this->transformMovieList($movies);
        return response()->json($movies);
    }

    /**
     * @param Collection $movies
     * @return Collection
     */
    private function transformMovieList(Collection $movies) :Collection {

        return $movies->transform(function ($item, $key) {
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
    }
}
