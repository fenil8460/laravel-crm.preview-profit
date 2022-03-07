<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leads extends Model
{
    protected $table = 'leads';
    public $primaryKey = 'id';
    public $timestamps = false;
}
