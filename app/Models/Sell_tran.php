<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Sell_tran extends Model
{
  protected $connection = 'other';

  protected $table = 'sell_tran';
  protected $primaryKey ='rec_no';
  public $incrementing = false;
  public $timestamps = false;
  public function Sell(){
    return $this->belongsTo(Sell::class,'order_no','order_no');
  }
  public function Item(){
    return $this->belongsTo(Item::class,'item_no','item_no');
  }
  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);

    if (Auth::check()) {

      $this->connection=Auth::user()->company;

    }
  }
}
