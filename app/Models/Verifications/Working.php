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
use Orchid\Filters\Types\Like;

class Working extends Model
{
    use SoftDeletes, HasFactory, AsSource, Filterable, Attachable;
    
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
        'etd',
        'date_import',
        'sut_id',
        'request_id',
        'number_poverki',
        'date_poverki',
        'name_poverki',
        'url_poverki',
        'date_publication',
        'verification_id'
    ];

    protected $allowedSorts = [
        'id',
        'status_id',
        'inv_no',
        'date_import',
        'number_poverki',
        'date_poverki',
        'date_publication',
        'created_at',
        'updated_at',
    ];

    protected $allowFilters = [
        'inv_no' => Like::class,
    ];

    protected $casts = [
        'date_import' => 'date',
        'date_poverki' => 'date',
        'date_publication' => 'date',
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

    public function scopeActive(Builder $query): Builder 
    {
        Log::info('Working model scopeActive method');
        $data = unserialize(Crypt::decryptString(request()->post('scope')));
        Log::info('scopeActive data:' . $data);

        return $query; 
    }
}
