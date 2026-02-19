<?php

namespace App\Filament\Resources\Mutations\Pages;

use App\Models\User;
use App\Models\Assets_item;
use App\Models\Mutation_item;
use Illuminate\Support\Facades\DB;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Mutations\MutationResource;

class CreateMutation extends CreateRecord
{
    protected static string $resource = MutationResource::class;

    protected array $selectedAssets = [];

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->selectedAssets = $data['assets'] ?? [];
        unset($data['assets']); // jangan masuk ke mutations table

        return $data;
    }

    protected function afterCreate(): void
    {
        // $mutation = $this->getRecord();
        // $userTo = User::find($mutation->user_id_to);

        // dd($this->selectedAssets);

        $mutation = $this->record;
        $userTo = User::find($mutation->user_id_to);

        foreach ($this->selectedAssets as $assetId) {

            // 1️⃣ insert ke mutation_items
            Mutation_item::create([
                'mutation_id' => $mutation->id,
                'item_id' => $assetId,
            ]);

            // 2️⃣ update asset ownership
            Assets_item::where('id', $assetId)->update([
                'user_id'        => $mutation->user_id_to,
                'numberOwner'    => DB::raw('numberOwner + 1'),
                'idCompany'      => $userTo->idCompany,
                'idRegional'     => $userTo->idRegion,
                'idBusinessUnit' => $userTo->idBisnisUnit,
                'idDepartment'   => $userTo->idDepartement,
                'idPosition'     => $userTo->idPosisi,
            ]);
        }
    }
}
