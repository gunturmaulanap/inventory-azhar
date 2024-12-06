<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('act_delivery_details', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('delivery_id');
        $table->string('image')->nullable();
        $table->timestamps();

        $table->foreign('delivery_id')->references('id')->on('deliveries')->onDelete('cascade');
    });
}

public function down()
{
    Schema::dropIfExists('act_delivery_details');
}

};
