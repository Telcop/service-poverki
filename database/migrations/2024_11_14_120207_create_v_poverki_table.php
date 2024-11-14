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
        Schema::create('v_poverki', function (Blueprint $table) {
            $table->id()->primary();
            
            $table->string('number', 10)->nullable()
                ->default(null)
                ->index()
                ->comment('Номер поверки');
        
            $table->date('date')->nullable()
                ->default(null)
                ->index()
                ->comment('Дата поверки');

            $table->string('name', 512)->nullable()
                ->default(null)
                ->index()
                ->comment('Название поверки для пользователя');

            $table->string('url', 256)->nullable()
                ->default(null)
                ->comment('URI файла поверки в хранилище');

            $table->date('date_publication')->nullable()
                ->default(null)
                ->index()
                ->comment('Дата публикации поверки на сайте');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('v_poverki');
    }
};
