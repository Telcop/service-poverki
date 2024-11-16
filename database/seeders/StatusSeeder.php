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
            ['status' => 'Не передан',
             'name' => 'Внесение доставок',
             'description' => 'Добавление инвойсов отгруженных поставок и фиксация доставленных на территорию РФ',
              'weight' => 100],

            ['status' => 'Подготовлен',
             'name' => 'Подготовка заявки',
             'description' => 'Оформление заявок на получение поверок',
             'weight' => 200],

            ['status' => 'Передан',
             'name' => 'Заявки',
             'description' => 'Учет заявок и внесение поверок',
             'weight' => 300],

            ['status' => 'Поверен',
             'name' => 'Поверки',
             'description' => 'Учет поверок медицинских изделий и выгрузка на сайт',
             'weight' => 400],

            ['status' => 'Выгружен',
             'name' => 'Выгруженные на сайт',
             'description' => 'Работа с поверками, выгруженными на сайт',
             'weight' => 500],

        ], uniqueBy: ['status'], update: ['weight']);
    }
}
