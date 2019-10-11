<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Period extends Model
{
		//
		use SoftDeletes;

		protected $table = "periods";

		protected $primaryKey = 'id';

		protected $fillable = ['start','finish','current','status'];

		protected $dates = ['deleted_at'];

		public function policys()
		{
			return $this->hasMany('App\Policy');
		}

		public function years()
		{
			return $this->hasMany('App\Year');
        }

		public function configurations()
		{
			return $this->hasMany('App\Configuration');
		}
}
