<?php

namespace App\Filament\Resources\Mutations\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DateTimePicker;
use App\Models\Assets_item;

class MutationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id_from')
                    ->label('User Asal')
                    ->relationship('userFrom', 'name')
                    ->getOptionLabelFromRecordUsing(fn($record) => "{$record->name} ({$record->NIK})")
                    ->searchable()
                    ->preload()
                    ->reactive()
                    ->required(),

                Select::make('user_id_to')
                    ->label('User Penerima')
                    ->relationship('userTo', 'name')
                    ->getOptionLabelFromRecordUsing(fn($record) => "{$record->name} ({$record->NIK})")
                    ->searchable()
                    ->preload()
                    ->required(),

                DateTimePicker::make('date')
                    ->label('Tanggal Mutasi')
                    ->default(now())
                    ->columnSpanFull(),

                Textarea::make('description')
                    ->label('Deskripsi')
                    ->rows(3)
                    ->columnSpanFull(),

                // ASSET LIST
                CheckboxList::make('assets')
                    ->label('Pilih Asset yang Akan Dimutasi')
                    ->options(function (callable $get) {

                        $userId = $get('user_id_from');
                        if (!$userId) return [];

                        return Assets_item::where('user_id', $userId)
                            ->get()
                            ->mapWithKeys(fn($asset) => [
                                $asset->id => "{$asset->item?->name} - {$asset->noAssetItem}"
                            ])
                            ->toArray();
                    })
                    // ->columns(2)
                    ->bulkToggleable()
                    ->required()
                    ->columnSpanFull()
                    ->dehydrated(true),
            ]);
    }
}
