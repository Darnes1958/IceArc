<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Main extends Model
{
  protected $connection = 'other';

  protected $table = 'main';
  protected $primaryKey ='no';
  public $incrementing = false;
  public $timestamps = false;
  public function Mainimg(){
    return $this->hasMany(Mainimg::class,'main_no','no');
  }
  public function Sell(){
    return $this->belongsTo(Sell::class,'order_no','order_no');

  }
    public function Bank(){
        return $this->belongsTo(Bank::class,'bank','bank_no');
    }
  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);

    if (Auth::check()) {

      $this->connection=Auth::user()->company;

    }
  }

}
