<?php

declare(strict_types=1);

namespace App\Orchid;

use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;
use Orchid\Support\Color;

class PlatformProvider extends OrchidServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @param Dashboard $dashboard
     *
     * @return void
     */
    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);

        // ...
    }

    /**
     * Register the application menu.
     *
     * @return Menu[]
     */
    public function menu(): array
    {
        return [
            Menu::make('Работа с поверками')
                ->icon('bs.card-list')
                ->title('Автоматизация')
                ->route('platform.verification.page100')
                ->active('*/verification/*')
                ->permission('platform.modules.automation'),

            Menu::make('Справочник Моделей')
                ->icon('bs.markdown')
                ->title('Справочники')
                ->route('platform.references.vendors')
                ->permission('platform.modules.references'),

            Menu::make('Справочник СУТ')
                ->icon('bs.book')
                ->route('platform.references.sut')
                ->permission('platform.modules.references'),

            Menu::make('Реестр поверок')
                ->icon('bs.collection')
                ->title('Отчеты')
                ->route('platform.reports.reestr')
                ->permission('platform.modules.reports'),

            Menu::make('Get Started')
                ->icon('bs.book')
                ->title('Navigation')
                ->route(config('platform.index'))
                ->permission('platform.developer.example'),

            Menu::make('Sample Screen')
                ->icon('bs.collection')
                ->route('platform.example')
                ->badge(fn () => 6)
                ->permission('platform.developer.example'),

            Menu::make('Form Elements')
                ->icon('bs.card-list')
                ->route('platform.example.fields')
                ->active('*/examples/form/*')
                ->permission('platform.developer.example'),

            Menu::make('Overview Layouts')
                ->icon('bs.window-sidebar')
                ->route('platform.example.layouts')
                ->permission('platform.developer.example'),
                
            Menu::make('Grid System')
                ->icon('bs.columns-gap')
                ->route('platform.example.grid')
                ->permission('platform.developer.example'),

            Menu::make('Charts')
                ->icon('bs.bar-chart')
                ->route('platform.example.charts')
                ->permission('platform.developer.example'),

            Menu::make('Cards')
                ->icon('bs.card-text')
                ->route('platform.example.cards')
                ->permission('platform.systems.users')
                ->divider()
                ->permission('platform.developer.example'),

            Menu::make(__('Пользователи'))
                ->icon('bs.people')
                ->route('platform.systems.users')
                ->permission('platform.systems.users')
                ->title(__('Контроль доступа')),

            Menu::make(__('Роли'))
                ->icon('bs.shield')
                ->route('platform.systems.roles')
                ->permission('platform.systems.roles')
                ->divider(),

            // Menu::make('Documentation')
            //     ->title('Docs')
            //     ->icon('bs.box-arrow-up-right')
            //     ->url('https://orchid.software/en/docs')
            //     ->target('_blank'),

            // Menu::make('Changelog')
            //     ->icon('bs.box-arrow-up-right')
            //     ->url('https://github.com/orchidsoftware/platform/blob/master/CHANGELOG.md')
            //     ->target('_blank')
            //     ->badge(fn () => Dashboard::version(), Color::DARK),
        ];
    }

    /**
     * Register permissions for the application.
     *
     * @return ItemPermission[]
     */
    public function permissions(): array
    {
        return [
            ItemPermission::group(__('System'))
                ->addPermission('platform.systems.roles', __('Roles'))
                ->addPermission('platform.systems.users', __('Users')),
            ItemPermission::group(__('Modules'))
                ->addPermission('platform.modules.automation', 'Автоматизация')
                ->addPermission('platform.modules.reports', 'Отчеты')
                ->addPermission('platform.modules.references', 'Справочники'),
            ItemPermission::group(__('Admin'))
                ->addPermission('platform.admin.logging', 'Логирование и восстановление'),
            ItemPermission::group(__('Developer'))
                ->addPermission('platform.developer.example', 'Примеры')
        ];
    }
}
