<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\LoginCustom;
use App\Filament\Resources\Services\ServiceResource;
use App\Models\Service;
use App\Filament\Pages\Auth\RegisterCustom;
use App\Filament\Pages\Auth\RequestPasswordResetCustom;
use App\Filament\Pages\EditProfileCustom;
// use App\Filament\Widgets\AssetsItemsWidget;
use App\Filament\Widgets\BppbStatusStats;
use App\Filament\Widgets\DashboardOverviewStats;
use Caresome\FilamentAuthDesigner\AuthDesignerPlugin;
use Caresome\FilamentAuthDesigner\Enums\MediaPosition;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class SisPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('sis')
            ->path('sis')
            ->passwordReset()
            ->profile(EditProfileCustom::class, isSimple: false)
            ->brandName('Sistem Informasi Sanbe')
            ->favicon(asset('img/logo.png'))
            ->darkMode(true, isForced: true)
            ->colors([
                'primary' => '#36b9cc',
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->widgets([
                DashboardOverviewStats::class,
                BppbStatusStats::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): string => <<<'HTML'
<style>
    .fi-ta-actions,
    .fi-ta-actions > div,
    .fi-ta-cell,
    .fi-ta-content {
        overflow: visible !important;
    }

    .fi-ta-actions .fi-dropdown-panel,
    .fi-ta-actions [data-placement] {
        z-index: 9999 !important;
    }
</style>
HTML,
            )
            ->renderHook(
                PanelsRenderHook::BODY_END,
                function (): string {
                    if (! auth()->check() || ! auth()->user()->hasRole('admin')) {
                        return '';
                    }

                    $pendingItCount = Service::query()->where('status_id', 3)->count();
                    if ($pendingItCount === 0) {
                        return '';
                    }

                    $path = parse_url(ServiceResource::getUrl('index'), PHP_URL_PATH);
                    if (! is_string($path) || $path === '') {
                        $path = '/sis/services';
                    }

                    return view('filament.hooks.service-sidebar-pending-it-badge', [
                        'count' => $pendingItCount,
                        'path' => $path,
                    ])->render();
                },
            )
            ->plugin(
                AuthDesignerPlugin::make()
                    ->defaults(
                        fn($config) => $config
                            ->media(asset('img/login_background.jpg'))
                            ->mediaPosition(MediaPosition::Cover)
                            ->blur(1)
                    )
                    ->login(
                        fn($config) => $config
                            ->media(asset('img/login_background.jpg'))
                            ->mediaPosition(MediaPosition::Cover)
                            ->blur(1)
                            ->usingPage(LoginCustom::class)
                    )
                    ->registration(
                        fn($config) => $config
                            ->media(asset('img/login_background.jpg'))
                            ->mediaPosition(MediaPosition::Right)
                            ->blur(1)
                            ->usingPage(RegisterCustom::class)
                    )
                    ->passwordReset(
                        fn($config) => $config
                            ->media(asset('img/login_background.jpg'))
                            ->mediaPosition(MediaPosition::Cover)
                            ->blur(1)
                            ->usingPage(RequestPasswordResetCustom::class)
                    )
            );
    }
}
