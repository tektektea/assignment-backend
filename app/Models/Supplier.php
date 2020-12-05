<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    protected $fillable=[
        'code','name','address_one','address_two','postal_code', 'contact',
    ];
    /**
     * @var mixed
     */
}
