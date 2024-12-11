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
        Schema::create('returs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->string('company')->nullable();
            $table->string('name')->nullable();
            $table->text('address')->nullable();
            $table->string('phone', 13)->nullable();
            $table->timestamps();
        });

        // Schema::create('goods_retur', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('retur_id')->constrained()->onDelete('cascade');
        //     $table->foreignId('goods_id')->constrained()->onDelete('cascade');
        //     $table->integer('qty');
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('goods_returs');
        Schema::dropIfExists('returs');
    }
};
