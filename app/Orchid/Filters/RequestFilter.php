<?php

namespace App\Orchid\Filters;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Select;
use App\Models\Verifications\Request as ModelRequest;

class RequestFilter extends Filter
{
    /**
     * The displayable name of the filter.
     *
     * @return string
     */
    public function name(): string
    {
        return __('Request');
    }

    /**
     * The array of matched parameters.
     *
     * @return array|null
     */
    public function parameters(): ?array
    {
        return ['number'];
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
        $builder->whereHas('request', function (Builder $query) {
            $query->where('number', $this->request->get('number'));
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
            Relation::make('number')
                ->fromModel(ModelRequest::class, 'number', 'number')
                ->displayAppend('full')
                ->empty()
                ->value($this->request->get('number') . config('custom.verification_number_request_mask'))
                ->title(__('Request')),
        ];
    }


}
