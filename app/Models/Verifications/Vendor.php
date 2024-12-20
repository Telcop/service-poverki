<?php

namespace App\Models\Verifications;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Orchid\Screen\AsSource;
use Orchid\Filters\Filterable;

class Vendor extends Model
{
    use SoftDeletes, HasFactory, AsSource, Filterable;
    
    protected $table = 'v_vendor';

    protected $fillable = [
        'vendore_code',
        'vendore_name',
    ];

    protected $allowedSorts = [
        'vendore_code'
    ];

    public function working(): HasMany 
    {
        return $this->hasMany(Working::class)->chaperone();
    }

    public function sut(): HasMany 
    {
        return $this->hasMany(Sut::class);
    }

}
