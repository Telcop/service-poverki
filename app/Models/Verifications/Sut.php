<?php

namespace App\Models\Verifications;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Crypt;
use Orchid\Screen\AsSource;
use Illuminate\Support\Facades\Log;


class Sut extends Model
{
    use SoftDeletes, HasFactory, AsSource;
    
    protected $table = 'v_sut';

    protected $fillable = [
        'vendor_id',
        'number',
        'date_from',
        'date_to'
    ];

    protected $casts = [
        'date_from' => 'date',
        'date_to' => 'date',
    ];

    public function working(): HasMany 
    {
        return $this->hasMany(Working::class)->chaperone();
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function getFullAttribute(): string
    {
        Log::info('Sut model getFullAttribute method');
        return sprintf('№%s (действует от %s до %s) для %s', $this->number, date('d.m.Y', strtotime($this->date_from)), date('d.m.Y', strtotime($this->date_to)), $this->vendor->vendore_code);
    }

    public function scopeActive(Builder $query): Builder 
    {
        Log::info('Sut model scopeVendor method');
        $data = unserialize(Crypt::decryptString(request()->post('scope')));
        // Log::info('scopeVendore data:' . $data);
        return $query->where('vendor_id', $data['parameters'][0]); //request()->post('scope')
    }


}
