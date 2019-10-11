<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;

use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;

class Plan extends Model implements AuthenticatableContract
{
	use Authenticatable, HasRoles, SoftDeletes;

	protected $table = "plans";

	protected $primaryKey = 'id';

	protected $fillable = ['department_id', 'name','status'];

	protected $dates = ['deleted_at'];

    public function position()
	{
		return $this->belongsTo('App\Position');
	}

	public function task()
	{
		return $this->belongsTo('App\Task');
	}

}
