<?php

namespace App\Models\Verifications;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Crypt;
use Orchid\Screen\TD;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Orchid\Screen\AsSource;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Like;
use Orchid\Filters\Types\Where;
use Orchid\Filters\Types\WhereDate;

use Illuminate\Support\Facades\Log;

class Vendor extends Model
{
    use SoftDeletes, HasFactory, AsSource, Filterable;
    
    protected $table = 'v_vendor';

    protected $fillable = [
        'vendore_code',
        'vendore_name',
    ];

    protected $allowedSorts = [
        'id',
        'vendore_code',
        'created_at',
        'updated_at'
    ];

    protected $allowedFilters = [
        'id' => Where::class,
        'vendore_code' => Like::class,
    ];


    public function working(): HasMany 
    {
        return $this->hasMany(Working::class)->chaperone();
    }

    public function sut(): HasMany 
    {
        return $this->hasMany(Sut::class);
    }

    public function scopeActive(Builder $query): Builder 
    {
        Log::info('Vendor model scopeVendor method');
        // $data = unserialize(Crypt::decryptString(request()->post('scope')));
        // Log::info('scopeVendore data:' . $data);
        return $query->orderBy('vendore_code', 'asc'); //request()->post('scope')
    }


}
