<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Buyimg extends Model
{
  protected $connection = 'other';

  public function Buy(){
    return $this->belongsTo(Buy::class,'buy_order_no','order_no');
  }
  public  function Jeha(){
    return $this->belongsTo(Jeha::class,'jeha_jeha_no','jeha_no');
  }


  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);

    if (Auth::check()) {

      $this->connection=Auth::user()->company;

    }
  }
}
