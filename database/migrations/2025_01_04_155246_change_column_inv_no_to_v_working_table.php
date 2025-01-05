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
        Schema::table('v_working', function (Blueprint $table) {
            $table->string('inv_no', 13)->nullable()
                ->default(null)
                // ->index()
                ->comment('Номер инвойса')
                ->change();
            //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('v_working', function (Blueprint $table) {
            $table->unsignedBigInteger('inv_no')->nullable()
                ->default(null)
                // ->index()
                ->comment('Номер инвойса')
                ->change();
            //
        });
    }
};
