<?php

namespace App;

use Iatstuti\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Policy extends Model
{
    //
		use SoftDeletes, CascadeSoftDeletes;

		protected $table = "policys";

		protected $primaryKey = 'id';

		protected $fillable = ['code','period_id'];

		protected $cascadeDeletes = ['targets'];

		protected $dates = ['deleted_at'];

		public function period()
		{
			return $this->belongsTo('App\Period');
		}

		public function targets()
		{
			return $this->hasMany('App\Target');
		}

		public function doings()
		{
			return $this->hasManyThrough('App\Doing', 'App\Target');
		}

		public function descriptions()
		{
        return $this->morphMany('App\Description', 'description');
		}		

}
