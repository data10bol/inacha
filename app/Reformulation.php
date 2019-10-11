<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reformulation extends Model
{
    //
	use SoftDeletes;

	protected $table = "reprogramations";

	protected $primaryKey = 'Id';

	protected $fillable = ['month', 'reasons', 'year', 'pivot1', 'status'];

	protected $dates = ['deleted_at'];
}
