<?php

namespace App\Models\Verifications;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Orchid\Screen\AsSource;

class Poverki extends Model
{
    use SoftDeletes, HasFactory, AsSource;
    
    protected $table = 'v_poverki';

    protected $fillable = [
        'number',
        'date',
        'name',
        'url',
        'date_publication'
    ];

    protected $casts = [
        'date' => 'date',
        'date_publication' => 'date',
    ];

    public function working(): HasMany 
    {
        return $this->hasMany(Working::class)->chaperone();
    }
}
