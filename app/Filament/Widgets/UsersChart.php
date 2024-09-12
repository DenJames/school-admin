<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class UsersChart extends ChartWidget
{
    protected static ?string $heading = 'Users Chart';

    protected function getData(): array
    {
        // https://github.com/Flowframe/laravel-trend
        // https://filamentphp.com/docs/3.x/widgets/charts
        $data = Trend::model(User::class)
            ->between(now()->subYear(), now())
            ->perMonth()
            ->count();

        return [
            'labels' => $data->map(fn(TrendValue $value) => $value->date),
            'datasets' => [
                [
                    'label' => 'Users',
                    'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}


