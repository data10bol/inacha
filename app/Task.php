<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
		//

	use SoftDeletes;

	protected $table = "tasks";

	protected $primaryKey = 'id';

	protected $fillable = [
		'code',
//        'user_id',
        'plan_id',
		'operation_id',
		'status',
		'reason',
	];

    // protected $cascadeDeletes = ['subtask'];

	protected $dates = ['deleted_at'];

/*	public function user()
	{
		return $this->belongsTo('App\User');
    }*/

    public function users()
	{
//		return $this->hasMany('App\Task');
        return $this->belongsToMany('App\User');
    }

	public function operation()
	{
		return $this->belongsTo('App\Operation');
    }

	public function subtasks()
	{
		return $this->hasMany('App\Subtask');
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

  public function reasons()
  {
    return $this->morphMany('App\Reason', 'reason');
  }

  public function plan()
  {
      return $this->hasOne('App\Plan','id','plan_id');
  }

}
