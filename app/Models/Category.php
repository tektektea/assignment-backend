<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable=['name', 'description'];

    public function materials()
    {
        return $this->belongsToMany(Material::class,'material_category','category_id','material_id');
    }
}
