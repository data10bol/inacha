<?php

namespace App;

use Iatstuti\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Action extends Model implements Auditable
{
    //
	use SoftDeletes, CascadeSoftDeletes;
	use \OwenIt\Auditing\Auditable;

	protected $table = "actions";

	protected $primaryKey = 'id';

	protected $fillable = ['code', 'goal_id', 'year', 'department_id', 'status', 'current'];

	//protected $cascadeDeletes = ['operation'];

	protected $dates = ['deleted_at'];


	public function goal()
	{
		return $this->belongsTo('App\Goal');
	}

	public function department()
	{
		return $this->belongsTo('App\Department');
	}

	public function operations()
	{
		return $this->hasMany('App\Operation');
	}

	public function tasks()
	{
		return $this->hasManyThrough('App\Task', 'App\Operation');
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
