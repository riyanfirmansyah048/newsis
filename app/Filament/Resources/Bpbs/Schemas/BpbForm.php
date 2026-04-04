<?php

namespace App\Filament\Resources\Bpbs\Schemas;

use App\Models\Bppb;
use App\Models\Purchase_order;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class BpbForm
{
    public static function configure(Schema $schema): Schema
    {
        $requestedPoId = request()->integer('po_id');

        return $schema
            ->components([
                $requestedPoId
                    ? Hidden::make('po_id')
                    ->default($requestedPoId)
                    : Select::make('po_id')
                    ->columnSpanFull()
                    ->label('Purchase Order')
                    ->options(function () {
                        $query = Purchase_order::query()
                            ->with(['bppb.user'])
                            ->whereDoesntHave('bpb');

                        if (! auth()->user()->hasRole('admin')) {
                            $query->where('user_id', auth()->id());
                        }

                        return $query
                            ->get()
                            ->mapWithKeys(function ($po) {
                                $bppbNo = $po->bppb?->noBppb ?? '-';
                                $userName = $po->bppb?->user?->name ?? '-';

                                return [
                                    $po->id => "{$bppbNo} | {$po->noPo} | {$userName}",
                                ];
                            })
                            ->toArray();
                    })
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Set $set, $state) {
                        $userId = Purchase_order::find($state)?->bppb?->user_id;
                        $set('user_id', $userId);
                    })
                    ->helperText('Pilih Purchase Order yang akan dibuatkan BPB.'),
                Hidden::make('user_id')
                    ->default(function () use ($requestedPoId) {
                        if ($requestedPoId) {
                            return Purchase_order::find($requestedPoId)?->bppb?->user_id;
                        }

                        return Bppb::find(request()->input('bppb_id'))?->user_id;
                    })
                    ->dehydrated(),
                DateTimePicker::make('dateBpb')
                    ->label('Tanggal BPB')
                    ->columnSpanFull()
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }
}
