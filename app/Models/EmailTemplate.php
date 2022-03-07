<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $connection = 'mysql';
    protected $table = 'email_templates';
    protected $primaryKey = 'id';
    // public $timestamps = false;
}
