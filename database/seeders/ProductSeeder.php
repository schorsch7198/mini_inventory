<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['name' => 'Wireless Mouse',       'sku' => 'WM-001', 'price' => 29.99,  'stock' => 50],
            ['name' => 'Mechanical Keyboard',   'sku' => 'MK-002', 'price' => 89.99,  'stock' => 30],
            ['name' => 'USB-C Hub',             'sku' => 'UH-003', 'price' => 45.00,  'stock' => 25],
            ['name' => 'Monitor Stand',         'sku' => 'MS-004', 'price' => 34.50,  'stock' => 40],
            ['name' => '27" 4K Monitor',        'sku' => 'MN-005', 'price' => 349.99, 'stock' => 15],
            ['name' => 'Webcam HD 1080p',       'sku' => 'WC-006', 'price' => 59.99,  'stock' => 35],
            ['name' => 'Desk Lamp LED',         'sku' => 'DL-007', 'price' => 24.99,  'stock' => 60],
            ['name' => 'Laptop Stand',          'sku' => 'LS-008', 'price' => 39.99,  'stock' => 20],
            ['name' => 'Noise Cancelling Headphones', 'sku' => 'NC-009', 'price' => 199.99, 'stock' => 10],
            ['name' => 'Mousepad XL',           'sku' => 'MP-010', 'price' => 14.99,  'stock' => 100],
            ['name' => 'HDMI Cable 2m',         'sku' => 'HC-011', 'price' => 9.99,   'stock' => 80],
            ['name' => 'Ethernet Cable 5m',     'sku' => 'EC-012', 'price' => 12.99,  'stock' => 70],
            ['name' => 'Portable SSD 1TB',      'sku' => 'PS-013', 'price' => 109.99, 'stock' => 18],
            ['name' => 'USB Microphone',        'sku' => 'UM-014', 'price' => 79.99,  'stock' => 22],
            ['name' => 'Wireless Charger',      'sku' => 'WC-015', 'price' => 19.99,  'stock' => 45],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}