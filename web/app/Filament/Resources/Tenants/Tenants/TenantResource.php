<?php

namespace App\Filament\Resources\Tenants\Tenants;

use App\Filament\Resources\Tenants\Tenants\Pages\CreateTenant;
use App\Filament\Resources\Tenants\Tenants\Pages\EditTenant;
use App\Filament\Resources\Tenants\Tenants\Pages\ListTenants;
use App\Filament\Resources\Tenants\Tenants\RelationManagers\StaysRelationManager;
use App\Filament\Resources\Tenants\Tenants\Schemas\TenantForm;
use App\Filament\Resources\Tenants\Tenants\Tables\TenantsTable;
use App\Models\Tenant;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class TenantResource extends Resource
{
    protected static ?string $model = Tenant::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-user-group';

    protected static \UnitEnum|string|null $navigationGroup = 'Manajemen';

    protected static ?string $navigationLabel = 'Penyewa';

    protected static ?string $modelLabel = 'Penyewa';

    protected static ?string $pluralModelLabel = 'Penyewa';

    public static function form(Schema $schema): Schema
    {
        return TenantForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TenantsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            StaysRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTenants::route('/'),
            'create' => CreateTenant::route('/create'),
            'edit' => EditTenant::route('/{record}/edit'),
        ];
    }
}
