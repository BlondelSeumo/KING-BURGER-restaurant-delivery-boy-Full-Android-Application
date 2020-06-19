<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payment_method';
    protected $primaryKey = 'id';
}
?>