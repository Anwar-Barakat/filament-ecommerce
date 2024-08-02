<?php

namespace App\Providers\Filament;

use Coolsam\Modules\ModulesPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Joaopaulolndev\FilamentEditProfile\FilamentEditProfilePlugin;
use Joaopaulolndev\FilamentEditProfile\Pages\EditProfilePage;
use Modules\Blog\Filament\Resources\PostResource;
use Modules\Ecommerce\Filament\Resources\BrandResource;
use Modules\Ecommerce\Filament\Resources\CategoryResource;
use Modules\Ecommerce\Filament\Resources\OrderResource;
use Modules\Ecommerce\Filament\Resources\PermissionResource;
use Modules\Ecommerce\Filament\Resources\ProductResource;
use Modules\Ecommerce\Filament\Resources\RoleResource;
use Modules\Ecommerce\Filament\Resources\UserResource;
use pxlrbt\FilamentSpotlight\SpotlightPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'danger' => Color::Red,
                'gray' => Color::Zinc,
                'info' => Color::Blue,
                'primary' => Color::Blue,
                'success' => Color::Green,
                'warning' => Color::Amber,
            ])
            ->globalSearchKeyBindings([
                'command+k',
                'ctrl+k',
            ])
            ->font('Poppins')
            ->favicon(asset('favicon.ico'))
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->navigation(function(NavigationBuilder $navigation) : NavigationBuilder{
                return $navigation->groups([
                    NavigationGroup::make('Management')->items([
                        ...CategoryResource::getNavigationItems(),
                        ...UserResource::getNavigationItems(),
                        ...RoleResource::getNavigationItems(),
                        ...PermissionResource::getNavigationItems(),
                    ]),
                    NavigationGroup::make('Shop')->items([
                        ...ProductResource::getNavigationItems(),
                        ...BrandResource::getNavigationItems(),
                        ...OrderResource::getNavigationItems(),
                    ]),
                    NavigationGroup::make('Blog')->items([
                        ...PostResource::getNavigationItems(),
                    ]),
                ]);
            })
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
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

                \Hasnayeen\Themes\Http\Middleware\SetTheme::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                ModulesPlugin::make(),
                SpotlightPlugin::make(),
                \Hasnayeen\Themes\ThemesPlugin::make(),
                FilamentEditProfilePlugin::make()
                    ->slug('my-profile')
                    ->setTitle('My Profile')
                    ->setNavigationLabel('My Profile')
                    ->setNavigationGroup('Management')
                    ->setIcon('heroicon-o-user')
                    ->setSort(10)
                    ->shouldShowDeleteAccountForm(true)
                    ->shouldShowBrowserSessionsForm()
                    ->shouldShowAvatarForm()
            ])
            ->userMenuItems([
                'profile' => MenuItem::make()
                    ->label(fn() => auth()->user()?->name)
                    ->url(fn(): string => EditProfilePage::getUrl())
                    ->icon('heroicon-m-user-circle')
            ]);
    }
}
