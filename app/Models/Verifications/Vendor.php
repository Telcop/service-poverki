<?php

namespace App\Models\Verifications;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Orchid\Screen\AsSource;

class Vendor extends Model
{
    use HasFactory, AsSource;
    
    protected $table = 'v_vendor';

    protected $fillable = [
        'vendore_code',
        'Vendore_name',
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
