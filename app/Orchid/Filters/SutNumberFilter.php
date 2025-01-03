<?php

namespace App\Orchid\Filters;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Select;
use App\Models\Verifications\Status;
use Orchid\Screen\TD;
use Orchid\Screen\Fields\Input;


class SutNumberFilter extends Filter
{
    /**
     * The displayable name of the filter.
     *
     * @return string
     */
    public function name(): string
    {
        return __('SUT registration number');
    }

    /**
     * The array of matched parameters.
     *
     * @return array|null
     */
    public function parameters(): ?array
    {
        return ['sutnumber'];
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
        return $builder->where('number', $this->request->get('sutnumber'));
    }

    /**
     * Get the display fields.
     *
     * @return Field[]
     */
    public function display(): iterable
    {
        return [
            Input::make('sutnumber')
            ->type('text')
            ->title(__('SUT registration number')),
        ];
    }
}
