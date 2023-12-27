<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Jeha extends Model
{
  protected $connection = 'other';

  protected $table = 'jeha';
  protected $primaryKey ='jeha_no';
  public $incrementing = false;
  public $timestamps = false;

  public function Mainimg(){
    return $this->hasMany(Mainimg::class,'jeha_jeha_no','jeha_no');
  }
  public function Buyimg(){
    return $this->hasMany(Buyimg::class,'jeha_jeha_no','jeha_no');
  }
  public function Buy(){
    return $this->hasMany(Buy::class,'jeha','jeha_no');
  }

  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);

    if (Auth::check()) {

      $this->connection=Auth::user()->company;

    }
  }
}
