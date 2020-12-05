<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialAllocation extends Model
{
    use HasFactory;
    protected $fillable=[
        'allocation_no','from','to','voucher_date','transfer_type','created_by','remark'
    ];
}
