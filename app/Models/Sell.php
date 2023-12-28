<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Sell extends Model
{
  protected $connection = 'other';

  protected $table = 'sells';
  protected $primaryKey ='order_no';
  public $incrementing = false;
  public $timestamps = false;
  public function Sell_tran(){
    return $this->hasMany(Sell_tran::class,'order_no','order_no');
  }

  public function Main(){
    return $this->hasMany(Main::class,'order_no','order_no');
  }
  public function Jehasell(){
    return $this->belongsTo(Jeha::class,'jeha','jeha_no');
  }
    public function Halls_name(){
        return $this->belongsTo(Halls_name::class,'place_no','hall_no');
    }
    public function stores_name(){
        return $this->belongsTo(Stores_name::class,'place_no','st_no');
    }

  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);

    if (Auth::check()) {

      $this->connection=Auth::user()->company;

    }
  }
}
