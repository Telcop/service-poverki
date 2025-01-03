<?php

namespace App\Models\Verifications;

use App\Models\Verifications\Working;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Orchid\Screen\AsSource;

class Request extends Model
{
    use SoftDeletes, HasFactory, AsSource;
    
    protected $table = 'v_request';

    protected $fillable = [
        'date_from',
        'number',
        'url_request'
    ];

    protected $casts = [
        'date_from' => 'date',
    ];

    public function working(): HasMany 
    {
        return $this->hasMany(Working::class)->chaperone();
    }

    public function getFullAttribute(): string
    {
        return sprintf('№%s%s от %s',
            $this->number, 
            config('custom.verification_number_request_mask'), 
            date('d.m.Y', strtotime($this->date_from))
        );
    }


    // public function scopeStatusRequest(Builder $query): Builder 
    // {
    //     Log::info('Sut model scopeVendor method');
    //     $data = unserialize(Crypt::decryptString(request()->post('scope')));
    //     Working::where('satus', $data['parameters'][0]);
    //     // Log::info('scopeVendore data:' . $data);
    //     return $query->whereIn('vendor_id', $data['parameters'][0]); //request()->post('scope')
    // }


}
