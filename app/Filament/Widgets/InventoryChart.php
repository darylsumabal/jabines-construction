<?php

namespace App\Filament\Widgets;

use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Openplain\FilamentShadcnTheme\Color;

class InventoryChart extends ApexChartWidget
{
    /**
     * Chart Id
     */
    protected static ?string $chartId = 'inventoryChart';

    /**
     * Widget Title
     */
    protected static ?string $heading = 'InventoryChart';


    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     */
    protected function getOptions(): array
    {
        return [
            'chart' => [
                'type' => 'bar',
                'height' => 300,
            ],
            'series' => [
                [
                    'name' => 'RoundedColumnChart',
                    'data' => [7, 4, 6, 10, 14, 7, 5, 9, 10, 15, 13, 18],
                ],
            ],
            'xaxis' => [
                'categories' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                'labels' => [
                    'style' => [
                        'colors' => Color::Default[400],
                        'fontWeight' => 600,
                    ],
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'colors' => Color::Default[400],
                        'fontWeight' => 600,
                    ],
                ],
            ],
            'colors' => [Color::Default[500]],
            'plotOptions' => [
                'bar' => [
                    'columnWidth' => '65%',
                ],
            ],
        ];
    }
}
