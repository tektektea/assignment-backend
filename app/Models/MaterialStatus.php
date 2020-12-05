<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialStatus extends Model
{
    use HasFactory;
    protected $fillable=['material_id','remark','status'];

    public function material(){
        return $this->belongsTo(Material::class);
    }
}
