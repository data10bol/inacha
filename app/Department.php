<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
		//

	use SoftDeletes;

	protected $table = "departments";

	protected $primaryKey = 'id';

	protected $fillable = ['name', 'dependency', 'user_id'];

	protected $dates = ['deleted_at'];

	public function positions()
	{
		return $this->hasMany('App\Position');
	}

    public function users()
    {
        return $this->hasManyThrough('App\Position', 'App\User');
    }

	public function responsable()
	{
		return $this->hasOne('App\User','id','user_id');
	}


	public function actions()
	{
		return $this->hasMany('App\Action');
	}

}
