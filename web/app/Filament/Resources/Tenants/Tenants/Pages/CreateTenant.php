<?php

namespace App\Filament\Resources\Tenants\Tenants\Pages;

use App\Filament\Resources\Tenants\Tenants\TenantResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTenant extends CreateRecord
{
    protected static string $resource = TenantResource::class;
}
