<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        // Dummy data for categories
        $categories = [
            [
                'id' => 1,
                'name' => 'Vegetables',
                'description' => 'Fresh, locally grown vegetables',
                'image' => 'https://images.unsplash.com/photo-1597362925123-77861d3fbac7?q=80&w=2940&auto=format&fit=crop',
                'product_count' => 25
            ],
            [
                'id' => 2,
                'name' => 'Fruits',
                'description' => 'Seasonal and exotic fruits',
                'image' => 'https://images.unsplash.com/photo-1610832958506-aa56368176cf?q=80&w=2940&auto=format&fit=crop',
                'product_count' => 18
            ],
            [
                'id' => 3,
                'name' => 'Dairy Products',
                'description' => 'Fresh dairy from local farms',
                'image' => 'https://images.unsplash.com/photo-1628088062854-d1870b4553da?q=80&w=2940&auto=format&fit=crop',
                'product_count' => 12
            ],
            [
                'id' => 4,
                'name' => 'Grains & Cereals',
                'description' => 'Organic grains and cereals',
                'image' => 'https://images.unsplash.com/photo-1608198093002-ad4e505484ba?q=80&w=2940&auto=format&fit=crop',
                'product_count' => 15
            ],
            [
                'id' => 5,
                'name' => 'Honey & Jams',
                'description' => 'Natural honey and homemade jams',
                'image' => 'https://images.unsplash.com/photo-1587049352846-4a222e784d38?q=80&w=2940&auto=format&fit=crop',
                'product_count' => 8
            ],
            [
                'id' => 6,
                'name' => 'Organic Products',
                'description' => 'Certified organic products',
                'image' => 'https://images.unsplash.com/photo-1542838132-92c53300491e?q=80&w=2940&auto=format&fit=crop',
                'product_count' => 20
            ]
        ];

        return view('shop.categories.index', compact('categories'));
    }
}