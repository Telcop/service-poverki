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
        Schema::create('v_request', function (Blueprint $table) {
            $table->id()->primary();

            $table->date('date_from')->nullable()
                ->default(null)
                ->index()
                ->comment('Дата заявки');
            
            $table->unsignedInteger('number')->nullable()
                ->default(null)
                ->index()
                ->comment('Номер заявки');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('v_request');
    }
};
