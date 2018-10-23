<?php

namespace App\Dev;

use Illuminate\Database\Eloquent\Model;

class Apikey extends Model
{
    protected $table = 'br_apikey';
    protected $primaryKey = 'key_id';
    protected $fillable = ['apikey', 'username', 'email', 'env', 'status'];
}
