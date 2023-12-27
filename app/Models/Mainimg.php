<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use mysql_xdevapi\Table;

class Mainimg extends Model
{
  protected $connection = 'other';


  public function Main(){
    return $this->belongsTo(Main::class,'main_no','no');
  }
  public  function Jeha(){
    return $this->belongsTo(Jeha::class,'jeha_jeha_no','jeha_no');
  }
  public  function Bank(){
    return $this->belongsTo(Bank::class,'bank_bank_no','bank_no');
  }

  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);

    if (Auth::check()) {

      $this->connection=Auth::user()->company;

    }
  }
}
