<?php

namespace App\Filament\Pages\Auth;

// use Filament\Pages\Page;
use Filament\Schemas\Schema;
// use Filament\Auth\Pages\Login;
use Caresome\FilamentAuthDesigner\Pages\Auth\Login;
use Filament\Forms\Components\TextInput;
use Illuminate\Validation\ValidationException;

class LoginCustom extends Login
{
    // protected string $view = 'filament.pages.auth.login-custom';
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
