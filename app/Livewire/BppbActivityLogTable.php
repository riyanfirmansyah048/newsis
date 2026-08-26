<?php

namespace App\Livewire;

use App\Models\Bpb;
use App\Models\Bppb;
use App\Models\Bppb_ink;
use App\Models\Bppb_item;
use App\Models\Bppb_software;
use App\Models\Expedition;
use App\Models\Purchase_order;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Component;
use Spatie\Activitylog\Models\Activity;
use App\Models\Bppb_status;

class BppbActivityLogTable extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public int $bppbId;

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y')
                    ->sortable(),

                TextColumn::make('causer.name')
                    ->label('User')
                    ->default('System')
                    ->badge()
                    ->color('primary'),
                // ->searchable(),

                TextColumn::make('subject_type')
                    ->label('Modul')
                    ->formatStateUsing(fn($state) => $this->formatSubjectType($state))
                    ->badge(),
                // ->searchable(),

                TextColumn::make('subject_label')
                    ->label('Data')
                    ->getStateUsing(fn(Activity $record) => $this->formatSubjectLabel($record)),
                // ->searchable(),

                TextColumn::make('description')
                    ->label('Aksi')
                    ->badge()
                    ->color(fn($state) => match (true) {
                        $state === 'created' => 'success',
                        $state === 'printed' => 'info',
                        $state === 'updated' => 'warning',
                        $state === 'deleted' => 'danger',
                        Str::startsWith((string) $state, 'rejected') => 'danger',
                        Str::startsWith((string) $state, 'cancelled') => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('changes')
                    ->label('Detail')
                    ->getStateUsing(fn(Activity $record) => $this->formatChanges($record))
                    ->wrap(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    protected function getTableQuery()
    {
        $itemIds = Bppb_item::withTrashed()
            ->where('bppb_id', $this->bppbId)
            ->pluck('id')
            ->toArray();

        $inkIds = Bppb_ink::withTrashed()
            ->where('bppb_id', $this->bppbId)
            ->pluck('id')
            ->toArray();

        $softwareIds = Bppb_software::withTrashed()
            ->where('bppb_id', $this->bppbId)
            ->pluck('id')
            ->toArray();

        $purchaseOrderIds = Purchase_order::withTrashed()
            ->where('bppb_id', $this->bppbId)
            ->pluck('id')
            ->toArray();

        $expeditionIds = Expedition::withTrashed()
            ->where('bppb_id', $this->bppbId)
            ->pluck('id')
            ->toArray();

        $bpbIds = Bpb::withTrashed()
            ->whereIn('po_id', $purchaseOrderIds)
            ->pluck('id')
            ->toArray();

        return Activity::query()
            ->with(['causer', 'subject'])
            ->where(function ($query) use ($itemIds, $inkIds, $softwareIds, $purchaseOrderIds, $expeditionIds, $bpbIds) {
                $query->where(function ($q) {
                    $q->where('subject_type', Bppb::class)
                        ->where('subject_id', $this->bppbId);
                });

                if (! empty($itemIds)) {
                    $query->orWhere(function ($q) use ($itemIds) {
                        $q->where('subject_type', Bppb_item::class)
                            ->whereIn('subject_id', $itemIds);
                    });
                }

                if (! empty($inkIds)) {
                    $query->orWhere(function ($q) use ($inkIds) {
                        $q->where('subject_type', Bppb_ink::class)
                            ->whereIn('subject_id', $inkIds);
                    });
                }

                if (! empty($softwareIds)) {
                    $query->orWhere(function ($q) use ($softwareIds) {
                        $q->where('subject_type', Bppb_software::class)
                            ->whereIn('subject_id', $softwareIds);
                    });
                }

                if (! empty($purchaseOrderIds)) {
                    $query->orWhere(function ($q) use ($purchaseOrderIds) {
                        $q->where('subject_type', Purchase_order::class)
                            ->whereIn('subject_id', $purchaseOrderIds);
                    });
                }

                if (! empty($expeditionIds)) {
                    $query->orWhere(function ($q) use ($expeditionIds) {
                        $q->where('subject_type', Expedition::class)
                            ->whereIn('subject_id', $expeditionIds);
                    });
                }

                if (! empty($bpbIds)) {
                    $query->orWhere(function ($q) use ($bpbIds) {
                        $q->where('subject_type', Bpb::class)
                            ->whereIn('subject_id', $bpbIds);
                    });
                }
            });
    }

    protected function formatSubjectType(?string $subjectType): string
    {
        return match ($subjectType) {
            Bppb::class => 'BPPB',
            Bppb_item::class => 'Item',
            Bppb_ink::class => 'Ink',
            Bppb_software::class => 'Software',
            Purchase_order::class => 'Purchase Order',
            Expedition::class => 'Expedition',
            Bpb::class => 'BPB',
            default => class_basename((string) $subjectType),
        };
    }

    protected function formatSubjectLabel(Activity $activity): string
    {
        $subject = $activity->subject;

        if (! $subject) {
            $subject = match ($activity->subject_type) {
                Bppb::class => Bppb::withTrashed()->find($activity->subject_id),
                Bppb_item::class => Bppb_item::withTrashed()->find($activity->subject_id),
                Bppb_ink::class => Bppb_ink::withTrashed()->find($activity->subject_id),
                Bppb_software::class => Bppb_software::withTrashed()->find($activity->subject_id),
                Purchase_order::class => Purchase_order::withTrashed()->find($activity->subject_id),
                Expedition::class => Expedition::withTrashed()->find($activity->subject_id),
                Bpb::class => Bpb::withTrashed()->find($activity->subject_id),
                default => null,
            };
        }

        if (! $subject) {
            return '-';
        }

        return match ($activity->subject_type) {
            Bppb::class => $subject->noBppb ?? 'BPPB',
            Bppb_item::class => $subject->item?->name ?? 'Item',
            Bppb_ink::class => $subject->ink?->name ?? 'Ink',
            Bppb_software::class => $subject->software?->name ?? 'Software',
            Purchase_order::class => $subject->noPo ?? 'Purchase Order',
            Expedition::class => $subject->noExpedition ?? 'Expedition',
            Bpb::class => $subject->noBpb ?? 'BPB',
            default => 'ID: ' . ($subject->id ?? '-'),
        };
    }

    protected function formatChanges(Activity $activity): string
    {
        $attributes = data_get($activity->properties, 'attributes', []);
        $old = data_get($activity->properties, 'old', []);
        $reason = trim((string) data_get($activity->properties, 'reason', ''));

        if ($activity->description === 'created') {
            return 'Data dibuat';
        }

        if ($activity->description === 'deleted') {
            return 'Data dihapus';
        }

        if ($activity->description === 'updated') {
            if (isset($attributes['status_id'])) {
                $fromId = $old['status_id'] ?? null;
                $toId = $attributes['status_id'] ?? null;

                $fromName = $fromId ? (Bppb_status::withTrashed()->find($fromId)?->name ?? $fromId) : '-';
                $toName = $toId ? (Bppb_status::withTrashed()->find($toId)?->name ?? $toId) : '-';

                return "Status berubah dari {$fromName} ke {$toName}";
            }

            $changedFields = array_keys($attributes);

            return empty($changedFields)
                ? 'Data diperbarui'
                : 'Field berubah: ' . implode(', ', $changedFields);
        }

        if ($activity->description === 'printed') {
            $printCount = data_get($activity->properties, 'print_count');

            if ($reason !== '') {
                return 'Print ulang alasan: ' . $reason;
            }

            return $printCount
                ? "Dokumen diprint ({$printCount}x)"
                : 'Dokumen diprint';
        }

        if (Str::startsWith((string) $activity->description, 'rejected')) {
            return $reason !== ''
                ? 'Alasan reject: ' . $reason
                : 'Data di-reject';
        }

        if (Str::startsWith((string) $activity->description, 'cancelled')) {
            return $reason !== ''
                ? 'Alasan cancel: ' . $reason
                : 'Data di-cancel';
        }

        return 'Aktivitas: ' . $activity->description;
    }

    public function render(): View
    {
        return view('livewire.bppb-activity-log-table');
    }
}
