<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;

    public function client(){
        return $this->belongsTo(Buyer::class,'client_id','id');
    }
    public function details(){
        return $this->hasMany(QuotationDetails::class,'quotation_id','id');
    }
    public function creator(){
        return $this->belongsTo(Admin::class,'added_by','id');
    }
}
