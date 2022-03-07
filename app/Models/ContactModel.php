<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactModel extends Model
{
    protected $table = 'contact_us';
    public $primaryKey = 'id';
    public $timestamps = false;
}
