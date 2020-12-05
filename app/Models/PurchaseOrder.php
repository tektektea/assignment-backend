<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;
    protected $fillable=[
        'order_no','voucher_date','remark','status','user_id','amount'
    ];

    public function lineItems(){
        return $this->hasMany(PurchaseOrderItem::class);
    }
}
