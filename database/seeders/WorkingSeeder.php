<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Verifications\Working;

class WorkingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Working::upsert([
            ['status_id' => 1, 'inv_no' => '5000067503', 'vendor_id' => 3, 'serial_start' => 'SNA231210001', 'serial_end' => 'SNA231212000', 'serial_start_int' => '231210001', 'serial_end_int' => '231212000', 'quantity' => 2000],
            ['status_id' => 1, 'inv_no' => '5000067503', 'vendor_id' => 2, 'serial_start' => 'SN5231220001', 'serial_end' => 'SN5231222000', 'serial_start_int' => '231220001', 'serial_end_int' => '231222000', 'quantity' => 2000],
            ['status_id' => 1, 'inv_no' => '5000067503', 'vendor_id' => 1, 'serial_start' => 'SNB231221001', 'serial_end' => 'SNB231222100', 'serial_start_int' => '231221001', 'serial_end_int' => '231222100', 'quantity' => 1100],
            ], uniqueBy: ['status_id', 'inv_no'], update: ['vendor_id']);
        Working::upsert([
            ['status_id' => 2, 'inv_no' => '5000067501', 'vendor_id' => 3, 'serial_start' => 'SNA231230001', 'serial_end' => 'SNA231232000', 'serial_start_int' => '231210001', 'serial_end_int' => '231212000', 'quantity' => 2000, 'date_import' => '2019-02-14', 'sut_id' => 2],
            ['status_id' => 2, 'inv_no' => '5000067501', 'vendor_id' => 2, 'serial_start' => 'SN5231240001', 'serial_end' => 'SN5231242000', 'serial_start_int' => '231220001', 'serial_end_int' => '231222000', 'quantity' => 2000, 'date_import' => '2019-02-14', 'sut_id' => 4],
            ['status_id' => 2, 'inv_no' => '5000067501', 'vendor_id' => 1, 'serial_start' => 'SNB231241001', 'serial_end' => 'SNB231242100', 'serial_start_int' => '231221001', 'serial_end_int' => '231222100', 'quantity' => 1100, 'date_import' => '2019-02-14', 'sut_id' => 6],
        ], uniqueBy: ['status_id', 'inv_no'], update: ['vendor_id']);
    }
}
