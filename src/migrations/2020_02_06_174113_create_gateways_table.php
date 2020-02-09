<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGatewaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::hasTable('gateways') || Schema::hasTable('gateways') or Schema::create('gateways', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('amount');
            $table->string('description')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->string('mobile')->nullable();
            $table->integer('track_id');
            $table->foreign('order_id')->references('id')->on('orders')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('status')->default(0);
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
        Schema::dropIfExists('gateways');
    }
}
