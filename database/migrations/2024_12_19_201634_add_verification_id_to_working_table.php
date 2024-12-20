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
        // Добавляем новые колонки в таблицу v_working
        Schema::table('v_working', function (Blueprint $table) {
            $table->unsignedBigInteger('verification_id')->nullable()->after('date_publication')
                ->default(null)
                ->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Удаляем колонки из таблицы v_working
        Schema::table('v_working', function (Blueprint $table) {
            $table->dropColumn('verification_id');
        });
    }
};