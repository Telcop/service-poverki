<?php

namespace App\Models\Verifications;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Vendor extends Model
{
    use HasFactory, AsSource;
    
    protected $table = 'v_vendor';

    protected $fillable = [
        'vendore_code',
        'Vendore_name',
    ];
}
