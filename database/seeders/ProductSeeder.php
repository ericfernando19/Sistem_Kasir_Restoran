<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // Makanan (category_id: 1)
            ['category_id' => 1, 'code' => 'MNU-0001', 'name' => 'Nasi Goreng Spesial', 'selling_price' => 25000, 'is_available' => true],
            ['category_id' => 1, 'code' => 'MNU-0002', 'name' => 'Mie Goreng Jawa', 'selling_price' => 22000, 'is_available' => true],
            ['category_id' => 1, 'code' => 'MNU-0003', 'name' => 'Ayam Bakar Madu', 'selling_price' => 35000, 'is_available' => true],
            ['category_id' => 1, 'code' => 'MNU-0004', 'name' => 'Soto Ayam Lamongan', 'selling_price' => 20000, 'is_available' => true],
            ['category_id' => 1, 'code' => 'MNU-0005', 'name' => 'Rawon Daging', 'selling_price' => 30000, 'is_available' => true],
            ['category_id' => 1, 'code' => 'MNU-0006', 'name' => 'Sate Ayam (10 tusuk)', 'selling_price' => 28000, 'is_available' => true],
            ['category_id' => 1, 'code' => 'MNU-0007', 'name' => 'Gado-Gado', 'selling_price' => 18000, 'is_available' => true],
            ['category_id' => 1, 'code' => 'MNU-0008', 'name' => 'Capcay Goreng', 'selling_price' => 20000, 'is_available' => true],

            // Minuman (category_id: 2)
            ['category_id' => 2, 'code' => 'MNU-0009', 'name' => 'Es Teh Manis', 'selling_price' => 5000, 'is_available' => true],
            ['category_id' => 2, 'code' => 'MNU-0010', 'name' => 'Teh Hangat', 'selling_price' => 4000, 'is_available' => true],
            ['category_id' => 2, 'code' => 'MNU-0011', 'name' => 'Es Jeruk', 'selling_price' => 7000, 'is_available' => true],
            ['category_id' => 2, 'code' => 'MNU-0012', 'name' => 'Kopi Hitam', 'selling_price' => 8000, 'is_available' => true],
            ['category_id' => 2, 'code' => 'MNU-0013', 'name' => 'Kopi Susu', 'selling_price' => 12000, 'is_available' => true],
            ['category_id' => 2, 'code' => 'MNU-0014', 'name' => 'Jus Alpukat', 'selling_price' => 15000, 'is_available' => true],
            ['category_id' => 2, 'code' => 'MNU-0015', 'name' => 'Jus Mangga', 'selling_price' => 15000, 'is_available' => true],
            ['category_id' => 2, 'code' => 'MNU-0016', 'name' => 'Air Mineral', 'selling_price' => 4000, 'is_available' => true],

            // Dessert (category_id: 3)
            ['category_id' => 3, 'code' => 'MNU-0017', 'name' => 'Pisang Goreng', 'selling_price' => 10000, 'is_available' => true],
            ['category_id' => 3, 'code' => 'MNU-0018', 'name' => 'Es Campur', 'selling_price' => 12000, 'is_available' => true],
            ['category_id' => 3, 'code' => 'MNU-0019', 'name' => 'Puding Coklat', 'selling_price' => 10000, 'is_available' => true],
            ['category_id' => 3, 'code' => 'MNU-0020', 'name' => 'Es Krim Vanilla', 'selling_price' => 8000, 'is_available' => true],

            // Snack (category_id: 4)
            ['category_id' => 4, 'code' => 'MNU-0021', 'name' => 'Kentang Goreng', 'selling_price' => 12000, 'is_available' => true],
            ['category_id' => 4, 'code' => 'MNU-0022', 'name' => 'Cireng Isi', 'selling_price' => 8000, 'is_available' => true],
            ['category_id' => 4, 'code' => 'MNU-0023', 'name' => 'Tahu Goreng', 'selling_price' => 7000, 'is_available' => true],
            ['category_id' => 4, 'code' => 'MNU-0024', 'name' => 'Tempe Mendoan', 'selling_price' => 7000, 'is_available' => true],
            ['category_id' => 4, 'code' => 'MNU-0025', 'name' => 'Perkedel Jagung', 'selling_price' => 8000, 'is_available' => true],
        ];

        foreach ($products as $product) {
            $product['purchase_price'] = $product['selling_price'];
            $product['stock'] = 0;
            $product['unit'] = 'pcs';
            $product['is_active'] = true;
            Product::create($product);
        }
    }
}
