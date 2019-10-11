<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Description extends Model
{
    //
    use SoftDeletes;

    protected $table = "descriptions";

    protected $primaryKey = 'id';

    protected $fillable = ['description', 'description_id', 'description_type'];

    protected $dates = ['deleted_at'];


    public function description()
    {
        return $this->morphTo();
    }

}
