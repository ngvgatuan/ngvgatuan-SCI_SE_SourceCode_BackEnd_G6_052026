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
        Schema::create('nha_cung_caps', function (Blueprint $table) {
            $table->id();
            $table->string('ten_nha_cung_cap');
            $table->string('so_dien_thoai');
            $table->string('avatar');
            $table->string('email');
            $table->string('password');
            $table->string('dia_chi');
            $table->integer('is_blocked');
            $table->integer('is_active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nha_cung_caps');
    }
};
