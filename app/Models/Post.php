<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'joining_date', 'leaving_date', 'user_id', 'department_id','remark'
    ];

    public function departments(){
        return $this->hasMany(Department::class);
    }
}
