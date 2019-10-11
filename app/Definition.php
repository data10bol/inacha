<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Definition extends Model
{
    //
  use SoftDeletes;

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