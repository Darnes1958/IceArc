<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Halls_name extends Model
{
    protected $connection = 'other';

    protected $table = 'halls_names';
    protected $primaryKey ='hall_no';
    public $incrementing = false;
    public $timestamps = false;
    public function Sell(){
        return $this->hasMany(Sell::class,'place_no','hall_no');

    }
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (Auth::check()) {

            $this->connection=Auth::user()->company;

        }
    }
}
