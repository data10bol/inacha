<?php

namespace App;

use Iatstuti\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Year extends Model
{
    //
		use SoftDeletes, CascadeSoftDeletes;

		protected $table = "years";

		protected $primaryKey = 'id';

		protected $fillable = ['name','period_id','status','current'];

		protected $dates = ['deleted_at'];

		public function period()
		{
			return $this->belongsTo('App\Period');
        }

		public function limits()
		{
			return $this->hasMany('App\Limit');
        }

}
