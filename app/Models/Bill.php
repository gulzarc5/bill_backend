<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    public function client(){
        return $this->belongsTo(Buyer::class,'client_id','id');
    }
    public function details(){
        return $this->hasMany(BillDetails::class,'bill_id','id');
    }

    public function quotations() {
        return $this->hasOne(Bill::class,'quote_id','id');
    }
    
}
