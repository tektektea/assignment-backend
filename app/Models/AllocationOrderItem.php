<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllocationOrderItem extends Model
{
    use HasFactory;
    protected $fillable=['material_id','material','quantity','allocation_order_id'];

    public function allocationOrder()
    {
        return $this->belongsTo(AllocationOrder::class);
    }
}
