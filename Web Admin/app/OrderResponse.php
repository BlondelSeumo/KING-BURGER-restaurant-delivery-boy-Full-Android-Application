<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderResponse extends Model
{
    protected $table = 'fooddelivery_food_desc';
    protected $primaryKey = 'id';

    
    public function itemdata()
    {      
        return $this->hasOne('App\Item', 'id', 'item_id');
    }
}
?>