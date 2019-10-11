<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Structure extends Model
{
    //
  use SoftDeletes;

  protected $table = "structures";

  protected $primaryKey = 'id';

  protected $fillable = [
    'code',
    'name',
    'current',
    'inversion',
    'status',
    'solution',
    'structure_id',
    'structure_type',
  ];

  protected $dates = ['deleted_at'];

  public function structure()
  {
    return $this->morphTo();
  }
}
