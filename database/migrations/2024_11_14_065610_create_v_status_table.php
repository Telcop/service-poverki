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

            $table->string('name',  30)->nullable()
                ->deafult(null)
                ->comment('Статус');
            $table->string('tab', 120)->nullable()
                ->deafult(null)
                ->comment('Название вкладки');
            $table->unsignedSmallInteger('weight')->nullable()
                ->deafult(null)
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
