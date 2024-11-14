<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Verifications\Status;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Status::create([
        //     'name' => 'Не передан',
        //     'tab' => 'Внесение доставок',
        //     'weight' => 100
        // ]);

        Status::upsert([
            ['name' => 'Не передан',    'tab' => 'Внесение доставок',   'weight' => 100],
            ['name' => 'Подготовлен',   'tab' => 'Подготовка заявки',   'weight' => 200],
            ['name' => 'Передан',       'tab' => 'Заявки',              'weight' => 300],
            ['name' => 'Поверен',       'tab' => 'Поверки',             'weight' => 400],
            ['name' => 'Выгружен',      'tab' => 'Выгруженные на сайт', 'weight' => 500],
        ], uniqueBy: ['name', 'tab'], update: ['weight']);
    }
}
