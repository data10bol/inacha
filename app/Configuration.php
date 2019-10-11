<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Configuration extends Model
{
    //
	use SoftDeletes;

	protected $table = "configurations";

	protected $primaryKey = 'id';

	protected $dates = ['deleted_at'];

	protected $fillable = [
		'period_id',
		'mission',
		'vision',
		'status',
		'edit', 
		'reconfigure',
	];

	public function period()
	{
		return $this->belongsTo('App\Period');
	}

	public function goals()
	{
		return $this->hasMany('App\Goal');
	}
}
