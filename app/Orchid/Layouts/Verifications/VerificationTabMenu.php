<?php

namespace App\Orchid\Layouts\Verifications;

use Orchid\Screen\Actions\Menu;
use Orchid\Screen\Layouts\TabMenu;
use app\Models\Verifications\Status;

class VerificationTabMenu extends TabMenu
{

    /**
     * Get the menu elements to be displayed.
     *
     * @return Menu[]
     */
    protected function navigations(): iterable
    {
        $tabs = Status::orderBy('weight', 'asc')->get()->toArray();
        // dd($tab);
        $result = [];
        foreach ($tabs as $tab) {
            $result[] = Menu::make($tab['name'])
            ->route('platform.verification.page' . $tab['weight']);
         }
        return $result;
    }
}
