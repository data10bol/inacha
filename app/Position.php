<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;

use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;

class Position extends Model implements AuthenticatableContract
{
	use Authenticatable, HasRoles, SoftDeletes;

	protected $table = "positions";

	protected $primaryKey = 'id';

	protected $fillable = ['department_id', 'name', 'status'];

	protected $dates = ['deleted_at'];

	public function department()
	{
		return $this->belongsTo('App\Department');
	}

	public function plans()
	{
		return $this->hasMany('App\Plan');
    }

    public function user()
    {
        return $this->hasOne('App\User');
    }

}
