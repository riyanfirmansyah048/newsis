<?php

namespace App\Filament\Pages\Auth;

use Filament\Schemas\Schema;
use Caresome\FilamentAuthDesigner\Pages\Auth\Login;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\ValidationException;

class LoginCustom extends Login
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('login')
                    ->label(__('Username / Email / NIK'))
                    ->required()
                    ->autocomplete()
                    ->autofocus(),

                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
            ]);
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label(__('filament-panels::auth/pages/login.form.password.label'))
            ->hint(filament()->hasPasswordReset() ? new HtmlString(Blade::render('<x-filament::link :href="filament()->getRequestPasswordResetUrl()" tabindex="3"> {{ __(\'filament-panels::auth/pages/login.actions.request_password_reset.label\') }}</x-filament::link>')) : null)
            ->helperText(new HtmlString('<span class="text-xs text-gray-500 dark:text-gray-400">Butuh bantuan? Hubungi Tim IT Tamansari ext : 1313/1314/1294</span>'))
            ->password()
            ->revealable(filament()->arePasswordsRevealable())
            ->autocomplete('current-password')
            ->required()
            ->extraInputAttributes(['tabindex' => 2]);
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        if (filter_var($data['login'], FILTER_VALIDATE_EMAIL)) {
            $loginType = 'email';
        } elseif (is_numeric($data['login'])) {
            $loginType = 'NIK';
        } else {
            $loginType = 'username';
        }

        return [
            $loginType => $data['login'],
            'password' => $data['password'],
        ];
    }

    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.login' => __('filament-panels::auth/pages/login.messages.failed'),
        ]);
    }
}
