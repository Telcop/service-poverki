<?php

namespace App\Orchid\Selections;

use Orchid\Filters\Filter;
use Orchid\Screen\Layouts\Selection;
use App\Orchid\Filters\IdFilter;
use App\Orchid\Filters\StatusFilter;
use App\Orchid\Filters\VendorFilter;
use App\Orchid\Filters\SerialNumberFilter;
use App\Orchid\Filters\InvoiceFilter;
use App\Orchid\Filters\RequestFilter;
use App\Orchid\Filters\CertificateFilter;
use App\Orchid\Filters\SutNumberFilter;

class VendorOperatorSelection extends Selection
{
    // public $template = self::TEMPLATE_LINE;

    /**
     * @return Filter[]
     */
    public function filters(): iterable
    {
        return [    
            IdFilter::class,        
            VendorFilter::class,
        ];
    }
}
