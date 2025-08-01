<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Category extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name','photo','slug', 'type_id'
    ];

    protected $hidden = [
        
    ];

    // Relasi ke model Type
    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id', 'id');
    }
}
