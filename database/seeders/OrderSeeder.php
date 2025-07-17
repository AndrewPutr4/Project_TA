<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
// Model-model di bawah ini tidak lagi digunakan, namun bisa dibiarkan
// use App\Models\Order;
// use App\Models\OrderItem;
// use App\Models\Menu;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Method ini sengaja dikosongkan untuk mencegah pembuatan data sampel.
     */
    public function run(): void
    {
        // Seeder ini tidak menjalankan perintah apa pun.
        // Ini akan mencegah pembuatan data order sampel secara otomatis.
        $this->command->info('OrderSeeder dilewati, tidak ada data sampel yang dibuat.');
    }
}