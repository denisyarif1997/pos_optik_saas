<?php

namespace Database\Factories;

use App\Models\StockLensMatrix;
use Illuminate\Database\Eloquent\Factories\Factory;

class StockLensMatrixFactory extends Factory
{
    protected $model = StockLensMatrix::class;

    public function definition(): array
    {
        $sph = fake()->randomElement([-2.00, -1.50, -1.00, -0.50, 0.00, 0.50, 1.00]);
        $cyl = fake()->randomElement([0.00, -0.50, -1.00, -1.50, -2.00]);

        return [
            'sph' => $sph,
            'cyl' => $cyl,
            'add_val' => fake()->randomElement([0.00, 1.00, 2.00]),
            'stock_qty' => fake()->numberBetween(0, 10),
            'min_stock_alert' => 2,
        ];
    }
}
