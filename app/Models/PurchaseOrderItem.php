<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem extends Model
{
    use HasFactory;

    protected $table = 'purchase_order_items';
    protected $fillable=[
        'purchase_order_id','material','quantity','material_id'
    ];

    public function order()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }
}
