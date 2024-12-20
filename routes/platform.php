<?php

declare(strict_types=1);

use App\Orchid\Screens\Verifications\VerificationIndexScreen;
use App\Orchid\Screens\Verifications\VerificationPreparedScreen;
use App\Orchid\Screens\Verifications\VerificationRequestScreen;
use App\Orchid\Screens\Verifications\VerificationPoverkiScreen;
use App\Orchid\Screens\Verifications\VerificationUploadedScreen;
use App\Orchid\Screens\Reports\ReestrScreen;
use App\Orchid\Screens\References\VendorsScreen;
use App\Orchid\Screens\References\SutScreen;

use App\Orchid\Screens\Examples\ExampleActionsScreen;
use App\Orchid\Screens\Examples\ExampleCardsScreen;
use App\Orchid\Screens\Examples\ExampleChartsScreen;
use App\Orchid\Screens\Examples\ExampleFieldsAdvancedScreen;
use App\Orchid\Screens\Examples\ExampleFieldsScreen;
use App\Orchid\Screens\Examples\ExampleGridScreen;
use App\Orchid\Screens\Examples\ExampleLayoutsScreen;
use App\Orchid\Screens\Examples\ExampleScreen;
use App\Orchid\Screens\Examples\ExampleTextEditorsScreen;
use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserListScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Trail;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the need "dashboard" middleware group. Now create something great!
|
*/

// Main
Route::screen('/main', PlatformScreen::class)
    ->name('platform.main');

// Platform > Profile
Route::screen('profile', UserProfileScreen::class)
    ->name('platform.profile')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Profile'), route('platform.profile')));

// Platform > System > Users > User
Route::screen('users/{user}/edit', UserEditScreen::class)
    ->name('platform.systems.users.edit')
    ->breadcrumbs(fn (Trail $trail, $user) => $trail
        ->parent('platform.systems.users')
        ->push($user->name, route('platform.systems.users.edit', $user)));

// Platform > System > Users > Create
Route::screen('users/create', UserEditScreen::class)
    ->name('platform.systems.users.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.systems.users')
        ->push(__('Create'), route('platform.systems.users.create')));

// Platform > System > Users
Route::screen('users', UserListScreen::class)
    ->name('platform.systems.users')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Users'), route('platform.systems.users')));

// Platform > System > Roles > Role
Route::screen('roles/{role}/edit', RoleEditScreen::class)
    ->name('platform.systems.roles.edit')
    ->breadcrumbs(fn (Trail $trail, $role) => $trail
        ->parent('platform.systems.roles')
        ->push($role->name, route('platform.systems.roles.edit', $role)));

// Platform > System > Roles > Create
Route::screen('roles/create', RoleEditScreen::class)
    ->name('platform.systems.roles.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.systems.roles')
        ->push(__('Create'), route('platform.systems.roles.create')));

// Platform > System > Roles
Route::screen('roles', RoleListScreen::class)
    ->name('platform.systems.roles')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Roles'), route('platform.systems.roles')));

// Example...
Route::screen('example', ExampleScreen::class)
    ->name('platform.example')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push('Example Screen'));

Route::screen('/examples/form/fields', ExampleFieldsScreen::class)->name('platform.example.fields');
Route::screen('/examples/form/advanced', ExampleFieldsAdvancedScreen::class)->name('platform.example.advanced');
Route::screen('/examples/form/editors', ExampleTextEditorsScreen::class)->name('platform.example.editors');
Route::screen('/examples/form/actions', ExampleActionsScreen::class)->name('platform.example.actions');

Route::screen('/examples/layouts', ExampleLayoutsScreen::class)->name('platform.example.layouts');
Route::screen('/examples/grid', ExampleGridScreen::class)->name('platform.example.grid');
Route::screen('/examples/charts', ExampleChartsScreen::class)->name('platform.example.charts');
Route::screen('/examples/cards', ExampleCardsScreen::class)->name('platform.example.cards');

//Route::screen('idea', Idea::class, 'platform.screens.idea');

Route::screen('verification/index', VerificationIndexScreen::class)
    ->name('platform.verification.page100')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push('Внесение доставок', route('platform.verification.page100')));

Route::screen('verification/prepared', VerificationPreparedScreen::class)
    ->name('platform.verification.page200')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.verification.page100')
        ->push('Подготовка заявки', route('platform.verification.page200')));

Route::screen('verification/request', VerificationRequestScreen::class)
    ->name('platform.verification.page300')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.verification.page200')
        ->push('Заявки', route('platform.verification.page300')));

Route::screen('verification/poverki', VerificationPoverkiScreen::class)
    ->name('platform.verification.page400')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.verification.page300')
        ->push('Поверки', route('platform.verification.page400')));

Route::screen('verification/uploaded', VerificationUploadedScreen::class)
    ->name('platform.verification.page500')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.verification.page400')
        ->push('Выгруженные на сайт', route('platform.verification.page500')));

Route::screen('reports/reestr', ReestrScreen::class)
    ->name('platform.reports.reestr');

Route::screen('references/vendors', VendorsScreen::class)
    ->name('platform.references.vendors')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push('Справочник моделей', route('platform.references.vendors')));

Route::screen('references/sut', SutScreen::class)
    ->name('platform.references.sut')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push('Справочник СУТ', route('platform.references.sut')));
