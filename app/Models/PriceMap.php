<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceMap extends Model
{
    use HasFactory;

    function material(){
        return $this->belongsTo(Material::class,'material_id','id');
    }

    function glassMm(){
        return $this->belongsTo(Glass_mm::class,'glass_mm_id','id');
    }
}
