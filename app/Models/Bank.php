<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Bank extends Model
{
  protected $connection = 'other';

  protected $table = 'bank';
  protected $primaryKey ='bank_no';
  public $incrementing = false;
  public $timestamps = false;

  public function Mainimg(){
    return $this->hasMany(Mainimg::class,'bank_bank_no','bank_no');
  }

  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);

    if (Auth::check()) {

      $this->connection=Auth::user()->company;

    }
  }
}
