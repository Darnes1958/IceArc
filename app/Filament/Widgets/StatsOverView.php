<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class StatsOverView extends BaseWidget
{
  protected int | string | array $columnSpan = 'full';
  protected function getColumns(): int
  {
    return 2;
  }
    protected function getStats(): array
    {
        return [
            Stat::make('مرحبا !!',Auth::user()->name)
              ->description('في شاشة أرشفة الوثائق')
              ->descriptionIcon('heroicon-s-check')
              ->color('success'),
          Stat::make('شركة ',Customer::where('Company',Auth::user()->company)->first()->CompanyName)
            ->description(Customer::where('Company',Auth::user()->company)->first()->CompanyNameSuffix)

            ->color('primary'),

        ];
    }
}
