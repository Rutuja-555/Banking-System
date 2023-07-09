<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accounts extends Model
{
    protected $primaryKey = "id";
    use HasFactory;

    public function user(){
        return $this->belongsTo(User::class,'acc_number','acc_number');
    }
}
