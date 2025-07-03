<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->string('id', 110)->primary();
            $table->string('payment_name', 191);
            $table->string('snap_token', 255);
            $table->dateTime('expiry', 3);
            $table->enum('status', ['PENDING_PAYMENT', 'PAID', 'CANCELED']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
