<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Definition extends Model implements Auditable
{
    //
  use SoftDeletes;
  use \OwenIt\Auditing\Auditable;

  protected $table = "definitions";

  protected $primaryKey = 'id';

  protected $fillable = [
    'description',
    'department_id',
    'dep_ponderation',
    'measure',
    'ponderation',
    'base',
    'aim',
    'describe',
    'pointer',
    'validation',
    'start',
    'finish',
    'definition_id',
    'definition_type',
    'in',
    'out'
  ];

  protected $dates = ['deleted_at'];


  public function definition()
  {
    return $this->morphTo();
  }
  public function department()
  {
    return $this->belongsTo('App\Department');
  }

}