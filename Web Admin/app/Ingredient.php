<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $table = 'food_ingredients';
    protected $primaryKey = 'id';
    
    public function categoryitem()
    {      
        return $this->hasOne('App\Category', 'id', 'category');
    }

    public function itemname()
    {      
        return $this->hasOne('App\Item', 'id', 'menu_id');
    }
   
   
}
?>