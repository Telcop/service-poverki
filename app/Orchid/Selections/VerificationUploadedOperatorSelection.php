<?php

namespace App\Orchid\Selections;

use Orchid\Filters\Filter;
use Orchid\Screen\Layouts\Selection;
use App\Orchid\Filters\IdFilter;
use App\Orchid\Filters\VendorModelFilter;
use App\Orchid\Filters\SerialNumberFilter;
use App\Orchid\Filters\CertificateFilter;

class VerificationUploadedOperatorSelection extends Selection
{
    // public $template = self::TEMPLATE_LINE;

    /**
     * @return Filter[]
     */
    public function filters(): iterable
    {
        return [    
            IdFilter::class,        
            VendorModelFilter::class,
            SerialNumberFilter::class,
            CertificateFilter::class,
        ];
    }
}
