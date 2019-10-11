<?php

namespace App;

use Iatstuti\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Target extends Model
{
    //
	use SoftDeletes, CascadeSoftDeletes;

	protected $table = "targets";

	protected $primaryKey = 'id';

	protected $fillable = ['code', 'policy_id'];

	protected $cascadeDeletes = ['doings'];

	protected $dates = ['deleted_at'];

	public function policy()
	{
		return $this->belongsTo('App\Policy');
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
