<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Result extends Model
{
    //
	use SoftDeletes;

	protected $table = "results";

	protected $primaryKey = 'id';

	protected $fillable = ['code', 'target_id'];

	protected $dates = ['deleted_at'];

	public function target()
	{
		return $this->belongsTo('App\Target');
	}

	public function doings()
	{
		return $this->hasMany('App\Doing');
	}

	public function descriptions()
	{
		return $this->morphMany('App\Description', 'description');
	}

}
