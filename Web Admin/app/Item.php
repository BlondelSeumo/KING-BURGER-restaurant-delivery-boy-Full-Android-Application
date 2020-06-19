<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'food_menu';
    protected $primaryKey = 'id';
    
    public function categoryitem()
    {      
        return $this->hasOne('App\Category', 'id', 'category');
    }
   
   
}
?>