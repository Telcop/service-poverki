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
        // Добавляем новую колонку в таблице v_request
        Schema::table('v_request', function (Blueprint $table) {
            $table->string('url_request', 256)->nullable()->after('number')
                ->default(null)
                ->comment('URI файла заявки в хранилище');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Удаляем колонку из таблицы v_request
        Schema::table('v_request', function (Blueprint $table) {
            $table->dropColumn('url_request');
        });
    }
};