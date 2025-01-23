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
                $name = $num . '.pdf';
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
}
