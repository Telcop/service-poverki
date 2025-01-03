<?php

namespace App\Models\Verifications;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Orchid\Screen\AsSource;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Like;

class Status extends Model
{
    use SoftDeletes, HasFactory, AsSource, Filterable;
    
    protected $table = 'v_status';

    protected $fillable = [
        'status',
        'name',
        'description',
        'weight'
    ];

    /**
     * @var array
     */
    protected $allowedFilters = [
        // 'id'          => Where::class,
        // 'status'      => Like::class,
    ];


    public function working(): HasMany 
    {
        return $this->hasMany(Working::class)->chaperone();
    }


}