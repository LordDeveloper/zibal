<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
            $table->string('mobile')->nullable();
            $table->integer('track_id')->nullable()->default(0);
            $table->unsignedBigInteger('order_id')->nullable();
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
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('gateways');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
