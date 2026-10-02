<?php

namespace App\Filament\Resources\Payments\Payments;

use App\Filament\Resources\Payments\Payments\Pages\CreatePayment;
use App\Filament\Resources\Payments\Payments\Pages\EditPayment;
use App\Filament\Resources\Payments\Payments\Pages\ListPayments;
use App\Filament\Resources\Payments\Payments\Pages\ViewPayment;
use App\Filament\Resources\Payments\Payments\Schemas\PaymentForm;
use App\Filament\Resources\Payments\Payments\Tables\PaymentsTable;
use App\Models\Payment;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-banknotes';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Pembayaran';

    protected static ?string $modelLabel = 'Pembayaran';

    protected static ?string $pluralModelLabel = 'Pembayaran';

    public static function form(Schema $schema): Schema
    {
        return PaymentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PaymentsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPayments::route('/'),
            'create' => CreatePayment::route('/create'),
            'view' => ViewPayment::route('/{record}'),
            'edit' => EditPayment::route('/{record}/edit'),
        ];
    }
}
