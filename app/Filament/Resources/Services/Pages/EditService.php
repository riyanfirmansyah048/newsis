<?php

namespace App\Filament\Resources\Services\Pages;

use Carbon\Carbon;
use App\Mail\ServiceAssignedToPicMail;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\Services\ServiceResource;
use Illuminate\Support\Facades\Mail;

class EditService extends EditRecord
{
    protected static string $resource = ServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
            Action::make('reject')
                ->label('Reject')
                ->action('rejectRecord')
                ->color('warning')
                ->visible(fn() => auth()->user()->hasRole('admin') && $this->record->status_id === 3),
            Action::make('approve')
                ->label('Approve')
                ->action('approveRecord')
                ->color('success')
                ->visible(fn() => auth()->user()->hasRole('admin') && $this->record->status_id === 3),
            Action::make('finish')
                ->label('Selesai (Barang di IT)')
                ->action('finishRecord')
                ->color('success')
                ->visible(fn() => auth()->user()->hasRole('admin') && in_array($this->record->status_id, [4, 5], true)),
            Action::make('finishall')
                ->label('Selesai (Barang Sudah Diserahkan)')
                ->action('finishAllRecord')
                ->color('success')
                ->visible(fn() => auth()->user()->hasRole('admin') && $this->record->status_id === 6),
        ];
    }

    public function rejectRecord()
    {
        $this->record->update([
            'status_id' => 2,
            'received_date' => Carbon::now(),
            'solution_id' => 6,
        ]);
        Notification::make()
            ->title('Pengajuan service berhasil ditolak!')
            ->success()
            ->send();

        $this->redirect($this->getResource()::getUrl('edit', ['record' => $this->record->getKey()]));
    }
    public function approveRecord()
    {
        $this->record->update([
            'status_id' => 4,
            'received_date' => Carbon::now(),
        ]);
        Notification::make()
            ->title('Barang Di terima di IT')
            ->success()
            ->send();
        $this->redirect($this->getResource()::getUrl('edit', ['record' => $this->record->getKey()]));
    }
    public function finishRecord()
    {
        $this->record->update([
            'status_id' => 6,
        ]);
        Notification::make()
            ->title('Selesai (Barang di IT)')
            ->success()
            ->send();
        $this->redirect($this->getResource()::getUrl('edit', ['record' => $this->record->getKey()]));
    }
    public function finishAllRecord()
    {
        $this->record->update([
            'status_id' => 7,
        ]);
        Notification::make()
            ->title('Selesai (Barang Sudah Diserahkan)')
            ->success()
            ->send();
        $this->redirect($this->getResource()::getUrl('edit', ['record' => $this->record->getKey()]));
    }

    protected function afterSave(): void
    {
        if (! $this->record->wasChanged('ic_id')) {
            return;
        }

        $picId = $this->record->ic_id;
        if (! $picId) {
            return;
        }

        $pic = User::query()->find($picId);
        $email = trim((string) ($pic?->email ?? ''));
        if ($email === '' || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            return;
        }

        $this->record->loadMissing(['user', 'item', 'status', 'icUser']);

        Mail::to($email)->send(new ServiceAssignedToPicMail($this->record));
    }
}
