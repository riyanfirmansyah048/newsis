<?php

namespace App\Filament\Resources\Bpbs\Schemas;

use App\Models\Bppb;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DateTimePicker;

class BpbForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('po_id')
                    ->default(fn() => request()->input('po_id')),
                Hidden::make('user_id')
                    ->default(fn() => Bppb::find(request()->input('bppb_id'))?->user_id),
                DateTimePicker::make('dateBpb')
                    ->label('Tanggal BPB')
                    ->columnSpanFull()
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }
}
