<?php

namespace App\Models\Verifications;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Orchid\Screen\AsSource;

class Sut extends Model
{
    use HasFactory, AsSource;
    
    protected $table = 'v_sut';

    protected $fillable = [
        'vendor_id',
        'number',
        'date_from',
        'date_to'
    ];

    public function working(): HasMany 
    {
        return $this->hasMany(Working::class)->chaperone();
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }


}
