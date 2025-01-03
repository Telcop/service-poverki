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


class SerialNumberFilter extends Filter
{
    /**
     * The displayable name of the filter.
     *
     * @return string
     */
    public function name(): string
    {
        return __('Serial number');
    }

    /**
     * The array of matched parameters.
     *
     * @return array|null
     */
    public function parameters(): ?array
    {
        return ['serial'];
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
        $serial = (int)substr(trim(str_replace('_', '', $this->request->get('serial'))), -9);

        return $builder->where('serial_start_int', '<=', $serial)->where('serial_end_int', '>=', $serial);
    }

    /**
     * Get the display fields.
     *
     * @return Field[]
     */
    public function display(): iterable
    {
        return [
            Input::make('serial')
            ->mask('A{0,3}9{0,1}9{9}')
            ->type('text')
            ->title(__('Serial number')),
        ];
    }
}
