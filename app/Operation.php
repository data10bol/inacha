<?php

namespace App;

use Iatstuti\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Operation extends Model
{
    //

	use SoftDeletes, CascadeSoftDeletes;

	protected $table = "operations";

	protected $primaryKey = 'id';

	protected $fillable = [
		'code',
		'action_id',
		'status',
	];

	// protected $cascadeDeletes = ['task'];

	protected $dates = ['deleted_at'];

	public function action()
	{
		return $this->belongsTo('App\Action');
	}

	public function tasks()
	{
		return $this->hasMany('App\Task');
	}

	public function definitions()
	{
		return $this->morphMany('App\Definition', 'definition');
	}

	public function poas()
	{
		return $this->morphMany('App\Poa', 'poa');
	}

	public function structures()
	{
		return $this->morphMany('App\Structure', 'structure');
	}

}
