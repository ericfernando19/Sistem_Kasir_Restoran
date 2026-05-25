<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Makanan', 'description' => 'Hidangan utama restoran'],
            ['name' => 'Minuman', 'description' => 'Minuman segar dan hangat'],
            ['name' => 'Dessert', 'description' => 'Pencuci mulut dan kue'],
            ['name' => 'Snack', 'description' => 'Camilan ringan'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }
    }
}
