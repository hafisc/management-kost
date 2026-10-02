<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Pengaturan extends Page
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationLabel = 'Pengaturan';

    protected static ?string $title = 'Pengaturan';

    protected string $view = 'filament.pages.pengaturan';
}
