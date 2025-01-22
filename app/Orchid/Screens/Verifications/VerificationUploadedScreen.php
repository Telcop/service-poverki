<?php

namespace App\Orchid\Screens\Verifications;

use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Screen\Layouts\Modal;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use App\Models\Verifications\Verification;
use App\Models\Verifications\Working;
use App\Models\Verifications\Status;
use Orchid\Platform\Dashboard;
use App\Orchid\Layouts\Verifications\VerificationTabMenu;
use App\Orchid\Layouts\Verifications\VerificationUploadedCommandBarRows;
use App\Orchid\Layouts\Verifications\VarificationUploadedTable;
use App\Orchid\Selections\VerificationUploadedOperatorSelection;
use App\Models\Logging;
use Illuminate\Support\Facades\Auth;

class VerificationUploadedScreen extends Screen
{
    protected const TAB_NUMBER = 5; 
    protected $paginate = 50;
    protected $tab;
    protected $table;
    protected $disable_entering = true;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Dashboard $dashboard): iterable //
    {
        $dashboard->registerResource('scripts', '/js/custom.js');
        $this->tab = Status::orderBy('weight', 'asc')->offset(self::TAB_NUMBER - 1)->limit(1)->get()->first();
        $this->table = Working::where('status_id', $this->tab->id)->with('vendor')->with('sut')->with('request')
            ->filtersApplySelection(VerificationUploadedOperatorSelection::class)
            ->filters()->paginate($this->paginate); //->orderBy('id', 'desc')->get()

        return [
            'tab' => $this->tab,
            'table5' => $this->table, 
            'disable_entering' => $this->disable_entering,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return $this->tab->name;
    }

    /**
     * Display header description.
     */
    public function description(): ?string
    {
        return $this->tab->description;
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
            'platform.modules.automation',
        ];
    }


    /**
     * The screen's layout elements.
     *
     * @return string[]|\Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    { 
        return [
            VerificationTabMenu::class,
            VerificationUploadedCommandBarRows::class,
            VerificationUploadedOperatorSelection::class,
            VarificationUploadedTable::class,

            // ============================================= МОДАЛЬНЫЕ ОКНА ============================================
            //
            // Модальное окно восстановление удаленной записи
            Layout::modal('RestoreModal', Layout::rows([
                Input::make('id', 'id')
                    ->mask('9{1,10}')
            ]))
                ->title('Введите id записи для восстановления')
                ->size(Modal::SIZE_SM),
        ];
    }

    // ========================================================= ОБРАБОТКА ================================================================
    //
    // Обработка Восстановление удаленной записи
    public function restoreItem(Request $request)
    {
        $record = Working::withTrashed()->find($request->input('id'));
        if (!$record) {
            Toast::warning("Запись c id = " . $request->input('id') . " не найдена среди удаленных");
        } else {
            $record->restore();
            Toast::info("Запись c id = " . $request->input('id') . " восстановлена");
        }
    }

     // Обработка Деактивация поверок на сайте
    public function deactivateVerifications(Request $request): void
    {

        $error = [];
        $ok = [];
        foreach ($request->input('working') as $id) {
            $working = Working::find($id);
            Verification::find($working->verification_id)->delete();
            $working->verification_id = null;
            $working->date_publication = null;
            $working->status_id = self::statusId(-1);
            $working->save();
            if (!$working) {
                $error[] = $id;
                continue;  
            } else {
                $ok[] = $id;
            }
        }
        if (empty($error)) {
            Toast::info(self::poverkiMessage($ok))->delay(10000);
            Logging::setAction(Auth::user()->name, Logging::ACTION_PREV_STATUS, [
                'status_id' => self::statusId(-1),
                'ids' => $ok,
            ]);
        } elseif (empty($ok)) {
            Toast::warning(self::poverkiMessage($error, true))->delay(10000);
            Logging::setAction(Auth::user()->name, Logging::ACTION_PREV_STATUS, [
                'Error next status Uploaded ids' => $error
            ]);
        } else {
            Toast::error(self::poverkiMessage($ok) . self::poverkiMessage($error, true))->delay(10000);
            Logging::setAction(Auth::user()->name, Logging::ACTION_PREV_STATUS, [
                'status_id' => self::statusId(-1),
                'ids_ok' => $ok,
                'ids_error' => $error
            ]);
        }       
    }

    // Добавление/корректировка статуса
    private function statusItem($id)
    {
        return ['status_id' => $id];
    }
    
    private function poverkiMessage(Array $ids, $error = false)
    {
        $subject = count($ids) > 1 ? 'Поверки ' : 'Поверка ';
        $subject .= 'c id = [' . implode(', ', $ids) . '] ';
        $subject .= $error ? 'не ' : 'успешно ';
        $subject .= count($ids) > 1 ? 'деактивированы и ' : 'деактивирована и ';
        $subject .= $error ? 'по прежнему ' : 'не ';
        $subject .= count($ids) > 1 ? 'доступны для поиска на сайте.' : 'доступна для поиска на сайте '; 
        return $subject;
    }

    private function statusId($step = 0)
    {
        $statuses = Status::select('id')->orderBy('weight', 'asc')->get()->toArray();
        if (self::TAB_NUMBER > 1) {
            $result = $statuses[self::TAB_NUMBER - 1 + $step];
        } 
        return $result['id'] ?? null;
    }

}
