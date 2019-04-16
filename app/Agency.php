<?php
/* Author: Li JinQuan */

namespace App;

use App\Crisis;
use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{

    public function crisis()
    {
        return $this->belongsToMany(Crisis::class, 'crisis_agencies', 'crisis_id', 'agency_id');
    }
}
