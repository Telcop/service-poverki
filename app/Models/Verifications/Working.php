<?php

namespace App\Models\Verifications;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Orchid\Screen\AsSource;

class Working extends Model
{
    use HasFactory, AsSource;
    
    protected $table = 'v_working';

    protected $fillable = [
        'status_id',
        'inv_no',
        'vendor_id',
        'serial_start',
        'serial_end',
        'serial_start_int',
        'serial_end_int',
        'quantity',
        'date_import',
        'sut_id',
        'request_id',
        'poverki_id'
    ];

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function sut(): BelongsTo
    {
        return $this->belongsTo(Sut::class);
    }

    public function request(): BelongsTo
    {
        return $this->belongsTo(Request::class);
    }

    public function poverki(): BelongsTo
    {
        return $this->belongsTo(Poverki::class);
    }
}
