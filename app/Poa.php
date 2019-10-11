<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Poa extends Model
{
    //
  use SoftDeletes;

  protected $table = "poas";

  protected $primaryKey = 'id';

  protected $fillable = [
    'state',
    'value',
    'month',
    'm1',
    'm2',
    'm3',
    'm4',
    'm5',
    'm6',
    'm7',
    'm8',
    'm9',
    'm10',
    'm11',
    'm12',
    'success',
    'failure',
    'solution',
    'poa_id',
    'poa_type',
    'in',
    'out'
  ];

  protected $dates = ['deleted_at'];

  public function poa()
  {
    return $this->morphTo();
  }
}
