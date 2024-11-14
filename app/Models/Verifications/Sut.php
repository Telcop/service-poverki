<?php

namespace App\Models\Verifications;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
}
