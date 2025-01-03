<?php

namespace App\Orchid\Filters;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Select;
use App\Models\Verifications\Status;

class StatusFilter extends Filter
{
    /**
     * The displayable name of the filter.
     *
     * @return string
     */
    public function name(): string
    {
        return __('Status');
    }

    /**
     * The array of matched parameters.
     *
     * @return array|null
     */
    public function parameters(): ?array
    {
        return ['status'];
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
        $builder->whereHas('status', function (Builder $query) {
            $query->where('status', $this->request->get('status'));
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
            Select::make('status')
                ->fromModel(Status::class, 'status', 'status')
                ->empty()
                ->value($this->request->get('status'))
                ->title(__('Status')),
        ];
    }
}
