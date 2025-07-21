<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Type;

class TypeSeeder extends Seeder
{
    public function run()
    {
        Type::create(['name' => 'Game']);
        Type::create(['name' => 'Pulsa']);
    }
}
