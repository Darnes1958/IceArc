<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class MasCenter extends Model
{
  protected $connection = 'other';

  protected $table = 'MasCenters';
  protected $primaryKey ='CenterNo';
  public $incrementing = false;
  public $timestamps = false;

  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);

    if (Auth::check()) {

      $this->connection=Auth::user()->company;

    }
  }
}
