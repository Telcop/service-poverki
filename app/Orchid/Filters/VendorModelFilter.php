<?php

namespace App\Orchid\Filters;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Select;
use App\Models\Verifications\Vendor as ModelVendor;

class VendorModelFilter extends Filter
{
    /**
     * The displayable name of the filter.
     *
     * @return string
     */
    public function name(): string
    {
        return __('Vendor');
    }

    /**
     * The array of matched parameters.
     *
     * @return array|null
     */
    public function parameters(): ?array
    {
        return ['vendore_code'];
    }

    /**
     * Apply to a given Eloquent query builder.
     *
     * @param Builder $builder
     *
     * @return Builder
     */
    public function run(Builder $builder): Builder
    {
        return 
        $builder->whereHas('vendor', function (Builder $query) {
            $query->where('vendore_code', $this->request->get('vendore_code'));
        });
    }

    /**
     * Get the display fields.
     *
     * @return Field[]
     */
    public function display(): iterable
    {
        return [
            Select::make('vendore_code')
                ->fromModel(ModelVendor::class, 'vendore_code', 'vendore_code')
                ->empty()
                ->value($this->request->get('vendore_code'))
                ->title(__('Vendor')),
        ];
    }


}
