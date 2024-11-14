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
        Schema::create('v_vendor', function (Blueprint $table) {
            $table->id()->primary();

            $table->string('vendore_code', 20)
                ->unique()
                ->comment('Модель');

            $table->string('vendore_name', 512)->nullable()
                ->deafult(null)
                ->comment('Название СУТ');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('v_vendor');
    }
};
