<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop foreign key dulu biar tidak error
        Schema::table('pembelians', function (Blueprint $table) {
            $table->dropForeign(['distributor_id']);
        });

        // Rename tabel
        Schema::rename('distributors', 'pemasok');

        // Tambahkan lagi foreign key dengan nama tabel baru
        Schema::table('pembelians', function (Blueprint $table) {
            $table->foreign('distributor_id')
                  ->references('id')
                  ->on('pemasok')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pembelians', function (Blueprint $table) {
            $table->dropForeign(['distributor_id']);
        });

        Schema::rename('pemasok', 'distributors');

        Schema::table('pembelians', function (Blueprint $table) {
            $table->foreign('distributor_id')
                  ->references('id')
                  ->on('distributors')
                  ->cascadeOnDelete();
        });
    }
};