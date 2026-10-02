<?php

namespace App\Filament\Resources\Expenses\Expenses;

use App\Filament\Resources\Expenses\Expenses\Pages\CreateExpense;
use App\Filament\Resources\Expenses\Expenses\Pages\EditExpense;
use App\Filament\Resources\Expenses\Expenses\Pages\ListExpenses;
use App\Filament\Resources\Expenses\Expenses\Schemas\ExpenseForm;
use App\Filament\Resources\Expenses\Expenses\Tables\ExpensesTable;
use App\Models\Expenses\Expense;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class ExpenseResource extends Resource
{
    protected static ?string $model = Expense::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-arrow-trending-down';

    protected static \UnitEnum|string|null $navigationGroup = 'Management';

    public static function form(Schema $schema): Schema
    {
        return ExpenseForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ExpensesTable::configure($table);
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
            'index' => ListExpenses::route('/'),
            'create' => CreateExpense::route('/create'),
            'edit' => EditExpense::route('/{record}/edit'),
        ];
    }
}
