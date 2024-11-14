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
        Schema::create('v_working', function (Blueprint $table) {
            $table->id();

            $table->unsignedSmallInteger('status_id')->nullable()
                ->deafult(null);
            $table->foreign('status_id')
                ->references('id')
                ->on('v_status')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            
            $table->unsignedBigInteger('inv_no')->nullable()
                ->deafult(null)
                ->index()
                ->comment('Номер инвойса');

            $table->unsignedBigInteger('vendor_id')->nullable()
                ->deafult(null)
                ->index();
            $table->foreign('vendor_id')
                ->references('id')
                ->on('v_vendor')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('serial_start', 16)->nullable()
                ->deafult(null)
                ->comment('Начало серии');
            $table->string('serial_end', 16)->nullable()
                ->deafult(null)
                ->comment('Конец серии');
            $table->unsignedInteger('serial_start_int')->nullable()
                ->deafult(null)
                ->comment('Начало серии числовой');
            $table->unsignedInteger('serial_end_int')->nullable()
                ->deafult(null)
                ->comment('Конец серии числовой');
            $table->index(['serial_start_int', 'serial_end_int']);

            $table->unsignedInteger('quantity')->nullable()
                ->deafult(null)
                ->comment('Количество'); 

            $table->date('date_import')->nullable()
                ->deafult(null)
                ->index()
                ->comment('Дата ввоза на территорию РФ');

            $table->unsignedBigInteger('sut_id')->nullable()
                ->deafult(null)
                ->index();
            $table->foreign('sut_id')
                ->references('id')
                ->on('v_sut')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->unsignedBigInteger('request_id')->nullable()
                ->deafult(null)
                ->index();
            $table->foreign('request_id')
                ->references('id')
                ->on('v_request')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->unsignedBigInteger('poverki_id')->nullable()
                ->deafult(null)
                ->index();
            $table->foreign('poverki_id')
                ->references('id')
                ->on('v_poverki')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('v_working');
    }
};
