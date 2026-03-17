<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::create([
            'name'        => 'Creme Bio',
            'description' => 'Creme naturelle',
            'price'       => 99.99,
            'stock'       => 50,
            'category_id' => 2,
            'slug'        => 'creme-bio', 
        ]);

        Product::create([
            'name'        => 'Huile Argan',
            'description' => 'Huile pure',
            'price'       => 149.99,
            'stock'       => 30,
            'category_id' => 2,
            'slug'        => 'huile-argan', 
        ]);
    }
}