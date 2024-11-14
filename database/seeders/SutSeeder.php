<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Verifications\Sut;

class SutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Sut::upsert([
            ['vendor_id' => 3, 'number' => '82283-21', 'date_from' => '2021-02-14', 'date_to' => '2025-02-14'],
            ['vendor_id' => 3, 'number' => '68813-17', 'date_from' => '2017-10-05', 'date_to' => '2022-10-05'],
            ['vendor_id' => 2, 'number' => '82283-21', 'date_from' => '2021-02-14', 'date_to' => '2025-02-14'],
            ['vendor_id' => 2, 'number' => '68813-17', 'date_from' => '2017-10-05', 'date_to' => '2022-10-05'],
            ['vendor_id' => 1, 'number' => '82283-21', 'date_from' => '2021-02-14', 'date_to' => '2025-02-14'],
            ['vendor_id' => 1, 'number' => '68813-17', 'date_from' => '2017-10-05', 'date_to' => '2022-10-05'],
            ['vendor_id' => 4, 'number' => '82283-21', 'date_from' => '2021-02-14', 'date_to' => '2025-02-14'],
            ['vendor_id' => 5, 'number' => '68813-17', 'date_from' => '2017-10-05', 'date_to' => '2022-10-05'],
            ['vendor_id' => 5, 'number' => '82285-21', 'date_from' => '2021-02-14', 'date_to' => '2025-02-14'],
            ], uniqueBy: ['vendor_id', 'number'], update: ['date_from', 'date_to']);
    }
}