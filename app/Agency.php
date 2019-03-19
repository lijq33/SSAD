<?php

namespace App;

use App\Crisis;
use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    protected $fillable = ['name', 'slug'];

    public function crisis()
    {
        return $this->belongsToMany(Crisis::class, 'crisis_agencies', 'crisis_id', 'agency_id');
    }
}
