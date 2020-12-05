<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllocationOrder extends Model
{
    use HasFactory;

    protected $fillable = ['order_no','from', 'to', 'created_by', 'voucher_date', 'remark', 'status'];

    public function lineItems(){
        return $this->hasMany(AllocationOrderItem::class);
    }
}
