<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountType extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    public function children()
    {
        return $this->hasMany(DiscountType::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
