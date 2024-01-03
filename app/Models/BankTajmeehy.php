<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class BankTajmeehy extends Model
{
  protected $connection = 'other';

  protected $table = 'BankTajmeehy';
  protected $primaryKey ='TajNo';
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
