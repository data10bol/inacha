<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;

use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;

class User extends Model implements AuthenticatableContract
{
	use Authenticatable, HasRoles, SoftDeletes;

	protected $table = "users";

	protected $primaryKey = 'id';

    protected $fillable = ['employee',
                            'username',
                            'password',
                            'name',
                            'position_id',
                            'dependency',
                            'status',
                            'sidebar',
                            'ip',
                            'login_at'];

	protected $dates = ['deleted_at'];

	public function setPasswordAttribute($value)
	{
		if ($value) {
			$this->attributes['password'] = app('hash')->needsRehash($value) ? Hash::make($value) : $value;
		}
	}

	public function position()
	{
		return $this->belongsTo('App\Position');
    }

	public function tasks()
	{
//		return $this->hasMany('App\Task');
        return $this->belongsToMany('App\Task');
    }

}
