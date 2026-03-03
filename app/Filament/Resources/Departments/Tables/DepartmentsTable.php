<?php

namespace App\Filament\Resources\Departments\Tables;

use App\Filament\Resources\SubDepartments\SubDepartmentResource;
use App\Models\Department;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;

class DepartmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordActionsPosition(RecordActionsPosition::BeforeColumns)
            ->columns([
                TextColumn::make('businessunit.businessUnitName')
                    ->label('Bisnis Unit')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('departmentName')
                    ->label('Departemen')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('code')
                    ->label('Kode')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
                Action::make('view')
                    ->label('List Sub Department')
                    ->url(
                        fn(Department $department) =>
                        SubDepartmentResource::getUrl('index', [
                            'department_id' => $department->id,
                        ])
                    ),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
