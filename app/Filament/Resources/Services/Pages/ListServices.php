<?php

namespace App\Filament\Resources\Services\Pages;

use App\Filament\Resources\Services\ServiceResource;
use App\Filament\Widgets\ServiceMonthlyTrendChart;
use App\Filament\Widgets\ServicePerPicChart;
use App\Filament\Widgets\ServicePicStatusChart;
use App\Filament\Widgets\ServicePicStats;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListServices extends ListRecords
{
    protected static string $resource = ServiceResource::class;

    public function mount(): void
    {
        parent::mount();

        if (! ServiceResource::hasValidProfileContact()) {
            Notification::make()
                ->title('Profil belum lengkap')
                ->body('Lengkapi data Anda seperti Email, ext, dan Departemen terlebih dahulu sebelum membuat Service / Memo IT.')
                ->warning()
                ->persistent()
                ->send();

            $this->redirectRoute('filament.sis.auth.profile');
        }
    }

    protected function getHeaderWidgets(): array
    {
        $user = auth()->user();

        if (! $user?->hasRole('admin')) {
            return [];
        }

        return [
            ServicePicStats::class,
            ServicePerPicChart::class,
            ServicePicStatusChart::class,
            ServiceMonthlyTrendChart::class,
        ];
    }

    public function getHeaderWidgetsColumns(): int | array
    {
        return 2;
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
