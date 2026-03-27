<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\LoginCustom;
use App\Filament\Pages\Auth\RegisterCustom;
use App\Filament\Pages\EditProfileCustom;
use App\Filament\Widgets\AssetsItemsWidget;
use App\Filament\Widgets\BppbStatusStats;
use Caresome\FilamentAuthDesigner\AuthDesignerPlugin;
use Caresome\FilamentAuthDesigner\Data\AuthPageConfig;
use Caresome\FilamentAuthDesigner\Enums\MediaPosition;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Assets\Asset;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
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
            // ->sidebarCollapsibleOnDesktop()
            ->default()
            ->id('sis')
            ->path('sis')
            // ->login(LoginCustom::class)
            // ->registration(RegisterCustom::class)
            ->profile(EditProfileCustom::class, isSimple: false)
            ->brandName('Sistem Informasi Sanbe')
            ->favicon(asset('img/logo.png'))
            ->colors([
                'primary' => '#36b9cc',
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            // ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                BppbStatusStats::class,
                // AssetsItemsWidget::class,
                // AccountWidget::class,
                // FilamentInfoWidget::class,
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
            ->plugin(
                // START Auth Plugin-----------------------------------------------
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
                    ) // Uses defaults
                    ->registration(
                        fn($config) => $config
                            ->media(asset('img/login_background.jpg'))
                            ->mediaPosition(MediaPosition::Right)
                            ->blur(1)
                            ->usingPage(RegisterCustom::class)
                    ) // Uses defaults
                // END Auth Plugin-----------------------------------------------
            );
    }
}
