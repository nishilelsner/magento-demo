<?php

namespace Practice\NewArrivals\Block;

use Magento\Framework\View\Element\Template;

class NewArrivals extends Template
{
    /**
     * Get static product data for now (will be replaced with DB data later)
     *
     * @return array
     */
    public function getProducts(): array
    {
        // Hardcoded products for testing — will be replaced in Step 9
        return [
            [
                'name' => 'Holy Land Perfect Time Daily Firming Cream 50ml',
                'image' => 'https://via.placeholder.com/300x300',
                'price' => 80.80,
                'special_price' => null,
                'rating' => 0,
                'review_count' => 0,
                'product_url' => '#',
                'is_new' => false,
            ],
            [
                'name' => 'Lakme L2 Classic Leave-in Conditioner 300ml',
                'image' => 'https://via.placeholder.com/300x300',
                'price' => 35.92,
                'special_price' => null,
                'rating' => 0,
                'review_count' => 0,
                'product_url' => '#',
                'is_new' => true,
            ],
            [
                'name' => 'AG Care Reconstruct Vitamin C Mask 178ml',
                'image' => 'https://via.placeholder.com/300x300',
                'price' => 28.00,
                'special_price' => 24.00,
                'rating' => 0,
                'review_count' => 0,
                'product_url' => '#',
                'is_new' => false,
            ],
            [
                'name' => 'Holy Land Age Defence CC Cream SPF 50',
                'image' => 'https://via.placeholder.com/300x300',
                'price' => 60.00,
                'special_price' => null,
                'rating' => 5,
                'review_count' => 1,
                'product_url' => '#',
                'is_new' => false,
            ],
            [
                'name' => 'Test Product 5 for Slider',
                'image' => 'https://via.placeholder.com/300x300',
                'price' => 45.00,
                'special_price' => null,
                'rating' => 4,
                'review_count' => 3,
                'product_url' => '#',
                'is_new' => true,
            ],
        ];
    }
}
