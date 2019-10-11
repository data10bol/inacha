<?php

namespace App;

use Iatstuti\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Limit extends Model
{
    //
		use SoftDeletes, CascadeSoftDeletes;

		protected $table = "limits";

		protected $primaryKey = 'id';

		protected $fillable = ['date','year_id','month','status'];

		protected $dates = ['deleted_at'];

		public function year()
		{
			return $this->belongsTo('App\Year');
		}

}
