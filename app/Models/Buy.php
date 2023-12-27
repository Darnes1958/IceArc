<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Buy extends Model
{
  protected $connection = 'other';

  protected $table = 'buys';
  protected $primaryKey ='order_no';
  public $incrementing = false;
  public $timestamps = false;

  public function Buyimg(){
    return $this->hasMany(Buyimg::class,'buy_order_no','order_no');
  }
  public function Jeha(){
    return $this->belongsTo(Jeha::class,'jeha','jeha_no');
  }

  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);

    if (Auth::check()) {

      $this->connection=Auth::user()->company;

    }
  }
}
