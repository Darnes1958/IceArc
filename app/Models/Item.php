<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Item extends Model
{
  protected $connection = 'other';

  protected $table = 'items';
  protected $primaryKey ='item_no';
  public $incrementing = false;
  public $timestamps = false;
  public function Sell_tran(){
    return $this->hasMany(Sell_tran::class,'item_no','item_no');
  }

  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);

    if (Auth::check()) {

      $this->connection=Auth::user()->company;

    }
  }
}
