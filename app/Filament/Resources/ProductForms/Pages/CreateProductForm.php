<?php

namespace App\Filament\Resources\ProductForms\Pages;

use App\Filament\Resources\ProductForms\ProductFormResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProductForm extends CreateRecord
{
    protected static string $resource = ProductFormResource::class;
}
