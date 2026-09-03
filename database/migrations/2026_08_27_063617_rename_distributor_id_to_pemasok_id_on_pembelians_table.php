<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembelians', function (Blueprint $table) {
            $table->dropForeign(['distributor_id']);
        });

        Schema::table('pembelians', function (Blueprint $table) {
            $table->renameColumn('distributor_id', 'pemasok_id');
        });

        Schema::table('pembelians', function (Blueprint $table) {
            $table->foreign('pemasok_id')
                  ->references('id')
                  ->on('pemasok')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pembelians', function (Blueprint $table) {
            $table->dropForeign(['pemasok_id']);
        });

        Schema::table('pembelians', function (Blueprint $table) {
            $table->renameColumn('pemasok_id', 'distributor_id');
        });

        Schema::table('pembelians', function (Blueprint $table) {
            $table->foreign('distributor_id')
                  ->references('id')
                  ->on('pemasok')
                  ->cascadeOnDelete();
        });
    }
};