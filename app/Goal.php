<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Goal extends Model
{
    //
  use SoftDeletes;

  protected $table = "goals";

  protected $primaryKey = 'id';

  protected $dates = ['deleted_at'];

  protected $fillable = [
    'code',
    'doing_id',
    'configuration_id',
    'description',
  ];

  public function configuration()
  {
    return $this->belongsTo('App\Configuration');
  }

  public function doing()
  {
    return $this->belongsTo('App\Doing');
  }

  public function actions()
  {
    return $this->hasMany('App\Action');
  }
}
