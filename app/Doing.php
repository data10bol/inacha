<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Doing extends Model
{
    //
	use SoftDeletes;

	protected $table = "doings";

	protected $primaryKey = 'id';

	protected $fillable = ['code', 'result_id'];

	protected $dates = ['deleted_at'];

	public function result()
	{
		return $this->belongsTo('App\Result');
	}

	public function goals()
	{
		return $this->hasMany('App\Goal');
	}

	public function descriptions()
	{
		return $this->morphMany('App\Description', 'description');
	}

}
