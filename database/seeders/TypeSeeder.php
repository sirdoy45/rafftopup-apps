<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Type;
use Illuminate\Support\Str;

class TypeSeeder extends Seeder
{
    public function run()
    {
        Type::create([
            'name' => 'Game',
            'slug' => Str::slug('Game')
        ]);

        Type::create([
            'name' => 'Pulsa',
            'slug' => Str::slug('Pulsa')
        ]);
    }
}
