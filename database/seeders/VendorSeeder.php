<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Verifications\Vendor;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Vendor::upsert([
            ['vendore_code' => 'UA-780',   'vendore_name' => 'Прибор для измерения артериального давления и частоты пульса цифровой серии UА, модель UА-780'],
            ['vendore_code' => 'UA-888', 'vendore_name' => 'Прибор для измерения артериального давления и частоты пульса цифровой серии UА, модель UА-888 '],
            ['vendore_code' => 'UA-888AC',   'vendore_name' => 'Приборы для измерения артериального давления и частоты пульса цифровые серии UA, модель: UA-888 (с чехлом для хранения и адаптером сетевым)'],
            ['vendore_code' => 'UA-777',   'vendore_name' => 'Приборы для измерения артериального давления и частоты пульса цифровые серии UA модель:UA-777'],
            ['vendore_code' => 'DT-624',   'vendore_name' => 'Термометр электронный, модель DT-624'],
        ], uniqueBy: ['vendore_code'], update: ['vendore_name']);
    }
}
