<?php

namespace App\Models\Verifications;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Crypt;
use Orchid\Screen\AsSource;
use Orchid\Filters\Filterable;
use Illuminate\Support\Facades\Log;
use Orchid\Attachment\Attachable;

class Verification extends Model
{
    protected $table = 'verifications';

    protected $fillable = [
        'vendor_code',
        'serial_start',
        'serial_end',
        'pdf_name',
        'file_path',
        'serial_start_int',
        'serial_end_int'
    ];

}
