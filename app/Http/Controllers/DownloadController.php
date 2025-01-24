<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Orchid\Attachment\Models\Attachment;

class DownloadController extends Controller
{
    public static function downloadFile($storage, $num, $y, $m, $d, $filename)
    {
        $filePath = implode('/', [$y, $m, $d, $filename]);
        switch ($storage) {
            case "requests":
                $disk = 'ftp_requests';
                $name = 'Запрос ' . $num . config('custom.verification_number_request_mask') . ' на поверку.xlsx';
                break;
            case "poverki":
                $disk = 'ftp_poverki';
                $name = self::correction($num) . '.pdf';
                break; 
            case "import":
                $disk = "import";
                $attachment = Attachment::where('name', explode('.', $filename)[0])->first();    
                if (!empty($attachment)) {
                    $name = $attachment->original_name;
                } else {
                    return abort(404);
                }  
                break;
            default:
                $disk = 'local';
                $name = $filename;
        }
        return Storage::disk($disk)->download($filePath, $name);
    }

    private static function correction($num)
    {
        // Письмо № 1825-06 от 10.01.2025 о поверке, прибор UA-888AC (серийные номера SNA231230001-SNA231232000)
        // Письмо №167-01 от 20.01.2025_UA-604_5241200001-5241203000
        $search = ['№ ', ' о поверке, прибор ', ' (серийные номера ', ')'];
        $replace = ['№', '_', '_', ''];
        return str_replace($search, $replace, $num);
    }
    
}
