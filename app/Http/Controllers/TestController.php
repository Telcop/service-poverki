<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Settings;
use App\Models\Verifications\Sut;
use Illuminate\Support\Facades\Crypt;


class TestController extends Controller
{
    // Тестирование инкремента номера заявки
    public static function test01()
    {
        $model = "eyJpdiI6IjhGQUtJNjRJZlBMRVZ2a1FpR0paZnc9PSIsInZhbHVlIjoiM05uMUpGSncyNTFrVXZ6UjhhVXQyaHZyTHpQRllaM3lBTWM3dmNaNjR4az0iLCJtYWMiOiIyOWMyMzFiM2M3NWEyZGZkNDdkY2Y4OTkxNTM4MmQ1NTViMjEyOWM4ODE0YTFiYmE4MDMyMzc1Y2M4NWY1ZTI4IiwidGFnIjoiIn0=";
        $name = "eyJpdiI6IjJYNXlVTGp4aEx2bGY4OEJlNURlc2c9PSIsInZhbHVlIjoiWndLcnQxV25XMUduQjhudGhsMG5udz09IiwibWFjIjoiNTM5NmMyNjE5ZDQ5MTE2OTE4NTk3NzE0M2Y0NTBlNTE0ZWEyOWM5YjQyY2FiYTE1OTZmNmE0OTY0ZDNhM2JiZCIsInRhZyI6IiJ9";
        $scope = 
        "eyJpdiI6InZsQ3hCK2xqZGZiSW5qOXU2cFZiSmc9PSIsInZhbHVlIjoiYS9YeE83VWtWWGg5b2cvTGhtWUNnY0VvSmRhU0YrZXRHUzJwSm1wcHV2RGhZQkxIdDA4enB1cVFENm1USk55aDM4bkxwQno4bWdXZEhLOUZlWTMzQkp1RUR5VC9xRXFsRnkvd1h3eWowZEZwUFZGQjB6WFpYTVdtd0dTQ0JTbnIiLCJtYWMiOiJiZWI2Y2M3M2E5OGU5ZjFiZjQzOWRiYTE5ZjBiOWQxMDU3NzMwYTU2NjEzY2JmYWVmYjMwMWUwZjU5ZGZlMjQyIiwidGFnIjoiIn0=";
        // "eyJpdiI6IkdyWERrdWxTeWIvL2RuYTRLUUFUOUE9PSIsInZhbHVlIjoiQ2hVWm9MY2JZU0U3dXFDRGthOXhNN1k1cE52b2h3UnBzQzdHNjN5eExQc3R6US8wcG9GRmRXcDA5d0ZLOVZuaDR2T3phNTFNVzMyVzJtLzhPT2xBOHI0MmNUWDFwZy8yKzJFdzMwL3hnUzA9IiwibWFjIjoiMDFjYzdlNDk5NWJhZjI1OWRlYjIxOWJlZTJhYzI2ZTc0N2IzYTNjM2Y2YjUyYTY4Yzg1MWE1MzRjMDdiODI1OCIsInRhZyI6IiJ9";
        $data = unserialize(Crypt::decryptString($scope));

        // Working::where('satus', $data['parameters'][0]);
        dump($data);
        // echo Settings::incrementNmberRequest();
    } 
}
