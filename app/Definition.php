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

}