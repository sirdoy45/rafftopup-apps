<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'product_id',
        'user_id', // opsional jika user login
        'customer_phone',
        'input_data', // ID Game, Server, No HP, dll dalam bentuk JSON
        'payment_method',
        'status',
        'invoice',
        'total_price',
    ];

    protected $casts = [
        'input_data' => 'array', // Supaya otomatis didekode jadi array saat dipanggil
    ];

    // Relasi ke Produk
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relasi ke User (jika ingin menyimpan user login)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
