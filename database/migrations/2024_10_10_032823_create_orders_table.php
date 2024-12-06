<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->string('company')->nullable();
            $table->string('name')->nullable();
            $table->text('address')->nullable();
            $table->string('phone', 13)->nullable();
            $table->integer('total');
            $table->string('status')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });

        Schema::create('goods_order', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('goods_id')->constrained()->onDelete('cascade');
            $table->integer('cost');
            $table->integer('qty');
            $table->integer('subtotal');
            $table->timestamps();
        });

        Schema::create('retur_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('goods_id')->constrained()->onDelete('cascade');
            $table->integer('cost');
            $table->integer('retur_qty');
            $table->integer('subcashback');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods_order');
        Schema::dropIfExists('orders');
    }
};
