<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Pages\Dashboard;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use App\Filament\Pages\Auth\LoginCustom;
use Filament\Widgets\FilamentInfoWidget;
use App\Filament\Pages\EditProfileCustom;
use Filament\Http\Middleware\Authenticate;
use App\Filament\Pages\Auth\RegisterCustom;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Filament\Http\Middleware\AuthenticateSession;
use Caresome\FilamentAuthDesigner\AuthDesignerPlugin;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Caresome\FilamentAuthDesigner\Data\AuthPageConfig;
use Caresome\FilamentAuthDesigner\Enums\MediaPosition;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class SisPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->sidebarCollapsibleOnDesktop()
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
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
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
