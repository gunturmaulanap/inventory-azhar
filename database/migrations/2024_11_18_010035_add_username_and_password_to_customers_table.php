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
    Schema::table('customers', function (Blueprint $table) {
        $table->string('username')->nullable()->unique(); // Username harus unik
        $table->string('password')->nullable(); // Password bersifat opsional
    });
}

public function down()
{
    Schema::table('customers', function (Blueprint $table) {
        $table->dropColumn(['username', 'password']);
    });
}

};
