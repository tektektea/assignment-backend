<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialAllocationItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_id', 'material', 'allocation_id', 'quantity'
    ];
}
