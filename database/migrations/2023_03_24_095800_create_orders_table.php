<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('клиент');
            $table->unsignedBigInteger('address_id');
            $table->enum('delivery_type', ['pickup', 'delivery']);
            $table->enum('payment_type', ['cash', 'card']);
            $table->longText('description');
            $table->bigInteger('status')->default(10)->comment('0 - decline, 10 - in process, 15 - accepted, 20 - complete');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
