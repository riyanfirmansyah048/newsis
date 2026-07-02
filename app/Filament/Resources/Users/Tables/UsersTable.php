<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Enums\RecordActionsPosition;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            // ->recordActionsPosition(RecordActionsPosition::BeforeColumns)
            ->columns([
                Split::make([
                    // Avatar
                    ImageColumn::make('image')
                        ->disk('photo_profiles')
                        ->circular()
                        ->width(64)
                        ->height(64)
                        ->defaultImageUrl(asset('img/profile.png'))
                        ->grow(false),

                    // Name + Email
                    Stack::make([
                        TextColumn::make('name')
                            ->label('Nama')
                            ->weight('bold')
                            ->size('lg')
                            ->searchable()
                            ->sortable(),

                        TextColumn::make('email')
                            ->color('gray')
                            ->size('sm'),
                    ])->space(1),

                    // Right side info
                    Stack::make([
                        TextColumn::make('NIK')
                            ->label('NIK')
                            ->searchable()
                            ->sortable()
                            ->badge()
                            ->color('primary'),

                        TextColumn::make('company.companyName')
                            ->label('Perusahaan')
                            ->searchable()
                            ->sortable()
                            ->badge()
                            ->color('danger'),

                        TagsColumn::make('roles.name')
                            ->label('Roles')
                            ->separator(','),

                        TextColumn::make('resign')
                            ->label('Status')
                            ->formatStateUsing(fn($state) => $state ? 'Resign' : 'Active')
                            ->icon(fn($state) => $state ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                            ->color(fn($state) => $state ? 'danger' : 'success')
                            ->badge(),
                    ])->space(2),
                ])->from('md')->columnSpanFull(),
            ])
            ->contentGrid([
                'md' => 1,
                'xl' => 2,
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->recordActions([
                // Action::make('assets')
                //     ->label('List Assets')
                //     ->icon('heroicon-o-archive-box')
                //     ->color('info'),
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
