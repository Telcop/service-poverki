<?php

namespace App\Orchid\Screens\Reports;

use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Modal;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use App\Models\Verifications\Verification;
use App\Models\Verifications\Working;
use App\Models\Verifications\Status;
use Orchid\Platform\Dashboard;
use App\Orchid\Layouts\Verifications\VerificationTabMenu;
use App\Orchid\Layouts\Reports\ReportReestrCommandBarRows;
use App\Orchid\Layouts\Reports\ReportReestrTable;
use App\Orchid\Filters\StatusFilter;
use App\Orchid\Selections\ReestrOperatorSelection;

class ReestrScreen extends Screen
{
    protected $paginate = 50;
    protected $table;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        $this->table = Working::with('status')->with('vendor')->with('sut')->with('request')
            ->filtersApplySelection(ReestrOperatorSelection::class)
            ->filters()
            ->paginate($this->paginate); //->orderBy('id', 'desc')->get()

        return [
            'reestr' => $this->table, 
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Реестр поверок';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }    
    
    public function permission(): ?iterable
    {
        return [
            'platform.modules.reports',
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            // ReportReestrCommandBarRows::class,
            ReestrOperatorSelection::class,
            ReportReestrTable::class,
        ];
    }
}
