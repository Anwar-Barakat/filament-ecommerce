<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'title' => 'Electronics',
                'slug' => Str::slug('Electronics'),
                'parent_id' => null,
                'is_visible' => true,
                'description' => 'Electronic devices and gadgets'
            ],
            [
                'title' => 'Books',
                'slug' => Str::slug('Books'),
                'parent_id' => null,
                'is_visible' => true,
                'description' => 'Various kinds of books'
            ],
            [
                'title' => 'Clothing',
                'slug' => Str::slug('Clothing'),
                'parent_id' => null,
                'is_visible' => true,
                'description' => 'Men and women clothing'
            ],
            [
                'title' => 'Home & Kitchen',
                'slug' => Str::slug('Home & Kitchen'),
                'parent_id' => null,
                'is_visible' => true,
                'description' => 'Home appliances and kitchenware'
            ],
        ];

        foreach ($categories as $category) {
            if(is_null(\App\Models\Category::where('slug', $category['slug'])->first()))
                \App\Models\Category::create($category);
        }
    }
}
