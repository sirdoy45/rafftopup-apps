<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;

    // Untuk mass-assignment
    protected $fillable = ['name'];

    // Relasi ke kategori
    public function categories()
    {
        return $this->hasMany(Category::class);
    }
}
