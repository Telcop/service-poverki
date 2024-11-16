<?php

namespace App\Models\Verifications;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Orchid\Screen\AsSource;

class Request extends Model
{
    use HasFactory, AsSource;
    
    protected $table = 'v_request';

    public function working(): HasMany 
    {
        return $this->hasMany(Working::class)->chaperone();
    }

}
