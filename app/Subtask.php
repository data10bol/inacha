<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subtask extends Model
{
		//

	use SoftDeletes;

	protected $table = "subtasks";

	protected $primaryKey = 'id';

	protected $fillable = [
		'code',
        'task_id',
        'reason',
        'status',
        'description',
        'validation',
        'start',
        'finish'
	];

	protected $dates = ['deleted_at'];


	public function task()
	{
		return $this->belongsTo('App\Task');
	}

}
