<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'set_order_detail';
    protected $primaryKey = 'id';

     public function userdata()
    {      
        return $this->hasOne('App\AppUser', 'id', 'user_id');
    }
}
?>