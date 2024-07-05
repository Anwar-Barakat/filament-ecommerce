<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define the number of brand seeders you want to create
        $numberOfSeeders = 5;

        // Array of brand data
        $brands = [
            [
                'name' => 'Brand 1',
                'slug' => 'brand-1',
                'url' => 'https://example.com/brand-1',
                'primary_hex' => '#000000',
                'is_visible' => true,
                'description' => 'Brand 1 description',

            ],
            [
                'name' => 'Brand 2',
                'slug' => 'brand-2',
                'url' => 'https://example.com/brand-2',
                'primary_hex' => '#FF0000',
                'is_visible' => true,
                'description' => 'Brand 2 description',

            ],
            [
                'name' => 'Brand 3',
                'slug' => 'brand-3',
                'url' => 'https://example.com/brand-3',
                'primary_hex' => '#00FF00',
                'is_visible' => true,
                'description' => 'Brand 3 description',

            ],
            [
                'name' => 'Brand 4',
                'slug' => 'brand-4',
                'url' => 'https://example.com/brand-4',
                'primary_hex' => '#0000FF',
                'is_visible' => true,
                'description' => 'Brand 4 description',

            ],
            [
                'name' => 'Brand 5',
                'slug' => 'brand-5',
                'url' => 'https://example.com/brand-5',
                'primary_hex' => '#FFFF00',
                'is_visible' => true,
                'description' => 'Brand 5 description',

            ],
        ];

        // Create and save multiple brand seeders
        foreach ($brands as $brand) {
            if (is_null(Brand::where('slug', $brand['slug'])->first())) {
                Brand::create($brand);
            }
        }
    }
}
