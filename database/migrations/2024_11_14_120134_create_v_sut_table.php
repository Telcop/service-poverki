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
        Schema::create('v_sut', function (Blueprint $table) {
            $table->id()->primary();

            $table->unsignedBigInteger('vendor_id')->nullable()
                ->default(null)
                ->index();
            $table->foreign('vendor_id')
                ->references('id')
                ->on('v_vendor')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('number', 10)->nullable()
                ->default(null)
                ->unique()
                ->comment('Регистрационный номер');
            
            $table->date('date_from')->nullable()
                ->default(null)
                ->index()
                ->comment('Дата действия СУТа от');

            $table->date('date_to')->nullable()
                ->default(null)
                ->index()
                ->comment('Дата действия СУТа до');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('v_sut');
    }
};
