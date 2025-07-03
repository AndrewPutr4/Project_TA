<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('menus_id');
            $table->decimal('price', 65, 30);
            $table->bigInteger('quantity');
            $table->decimal('subtotal', 65, 30);
            $table->dateTime('order_date', 3);
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            // $table->foreign('menus_id')->references('id')->on('menus'); // Uncomment jika ada tabel menus
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_items');
    }
}
