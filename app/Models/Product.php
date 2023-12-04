<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';

    public $fillable = [
        'glsss_mm_id'
    ];
    function category(){
        return $this->belongsTo(Category::class,'category_id','id');
    }

    function material(){
        return $this->belongsTo(Material::class,'material_id','id');
    }

    function brand(){
        return $this->belongsTo(Brand::class,'brand_id','id');
    }

    function glassMm(){
        return $this->belongsTo(Glass_mm::class,'glass_mm_id','id');
    }

    public function priceMaps()
    {
        return $this->hasMany(PriceMap::class, 'material_id', 'material_id');
    }

}
