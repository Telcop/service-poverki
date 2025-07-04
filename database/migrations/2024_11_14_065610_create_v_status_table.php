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
        Schema::create('v_status', function (Blueprint $table) {
            $table->smallIncrements('id');

            $table->string('status',  30)->nullable()
                ->default(null)
                ->comment('Статус');
            $table->string('name', 120)->nullable()
                ->default(null)
                ->comment('Название вкладки');
            $table->string('description', 512)->nullable()
                ->default(null)
                ->comment('Описание вкладки');
            $table->unsignedSmallInteger('weight')->nullable()
                ->default(null)
                ->comment('Весовой коэффициент');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('v_status');
    }
};
