<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $fillable = ["title", "description", "country_id"];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function genres() {
        return $this->belongsToMany("App\Models\Genre");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function country() {
        return $this->belongsTo("App\Models\Country");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function image() {
        return $this->belongsTo("App\Models\Image");
    }
}
