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
        Schema::table('act_delivery_details', function (Blueprint $table) {
            $table->json('image')->nullable()->change(); // Ubah tipe kolom menjadi JSON
        });
    }

    public function down()
    {
        Schema::table('act_delivery_details', function (Blueprint $table) {
            $table->string('image')->nullable()->change(); // Kembalikan ke string jika perlu
        });
    }
};
