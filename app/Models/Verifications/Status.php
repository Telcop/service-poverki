<?php

namespace App\Models\Verifications;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Orchid\Screen\AsSource;

class Status extends Model
{
    use SoftDeletes, HasFactory, AsSource;
    
    protected $table = 'v_status';

    protected $fillable = [
        'status',
        'name',
        'description',
        'weight'
    ];

    public function working(): HasMany 
    {
        return $this->hasMany(Working::class)->chaperone();
    }

}
