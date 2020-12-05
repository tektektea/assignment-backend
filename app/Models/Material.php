<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;
    protected $fillable=['name','code','serial_no','color','cost_price','manufacture'];

    public function categories()
    {
        return $this->belongsToMany(Category::class,'material_category','material_id','category_id');
    }
    public function statuses(){
        return $this->hasMany(MaterialStatus::class);
    }
}
