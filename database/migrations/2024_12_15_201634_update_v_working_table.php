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
            $table->date('etd', 10)->nullable()->after('quantity')
                ->index()
                ->default(null)
                ->comment('ETD');

            $table->string('number_poverki', 10)->nullable()->after('poverki_id')
                ->default(null)
                ->index()
                ->comment('Номер поверки');

            $table->date('date_poverki')->nullable()->after('number_poverki')
                ->default(null)
                ->index()
                ->comment('Дата поверки');

            $table->string('name_poverki', 512)->nullable()->after('date_poverki')
                ->default(null)
                ->index()
                ->comment('Название поверки для пользователя');

            $table->string('url_poverki', 256)->nullable()->after('name_poverki')
                ->default(null)
                ->comment('URI файла поверки в хранилище');

            $table->date('date_publication')->nullable()->after('url_poverki')
                ->default(null)
                ->index()
                ->comment('Дата публикации поверки на сайте');
        });

        // Удаляем колонку poverki_id из таблицы v_working
        Schema::table('v_working', function (Blueprint $table) {
            $table->dropForeign(['poverki_id']);
            $table->dropColumn('poverki_id');
        });

        // Удаляем таблицу v_poverki
        Schema::dropIfExists('v_poverki');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Восстанавливаем таблицу v_poverki
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
            $table->softDeletes();
        });

        // Восстанавливаем колонку poverki_id в таблице v_working
        Schema::table('v_working', function (Blueprint $table) {
            $table->unsignedBigInteger('poverki_id')->nullable()->after('request_id')
                ->default(null)
                ->index();
            $table->foreign('poverki_id')
                ->references('id')
                ->on('v_poverki')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

        });

        // Удаляем колонки из таблицы v_working
        Schema::table('v_working', function (Blueprint $table) {
            $table->dropColumn('etd');
            $table->dropColumn('number_poverki');
            $table->dropColumn('date_poverki');
            $table->dropColumn('name_poverki');
            $table->dropColumn('url_poverki');
            $table->dropColumn('date_publication');
        });
    }
};