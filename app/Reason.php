<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reason extends Model
{
    //
  use SoftDeletes;

  protected $table = "reasons";

  protected $primaryKey = 'id';

  protected $fillable = [
    'user_id',
    'description',
    'reason_id',
    'reason_type',
  ];

  protected $dates = ['deleted_at'];

  public function structure()
  {
    return $this->morphTo();
  }
}
